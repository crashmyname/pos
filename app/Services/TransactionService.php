<?php

namespace App\Services;
use App\Models\Cashback;
use App\Models\DetailTransaction;
use App\Models\SetupTransaction;
use App\Models\Transaction;
use Bpjs\Framework\Helpers\Date;
use Bpjs\Framework\Helpers\DB;
use Bpjs\Framework\Helpers\Http\Http;
use Bpjs\Framework\Helpers\Validator;

class TransactionService
{
    // Service logic here
    private function invoiceNumber()
    {
        $currentDate = Date::parse(Date::Now())->format('Ymd');
        
        // Cari transaksi terakhir dengan format INV + tanggal hari ini
        $lastTransaction = Transaction::query()
            ->where('invoice_number', 'LIKE', "INV{$currentDate}%")
            ->orderBy('invoice_number', 'DESC')
            ->first();
        
        if ($lastTransaction && $lastTransaction->invoice_number) {
            // Extract sequence number (4 digit terakhir)
            $lastSequence = (int) substr($lastTransaction->invoice_number, 11);
            $newSequence = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // Tidak ada transaksi hari ini, mulai dari 0001
            $newSequence = '0001';
        }
        
        return 'INV' . $currentDate . $newSequence;
    }

    public function dailyTransaction()
    {
        $today = Date::parse(Date::Now())->format('Y-m-d');
        return Transaction::query()
            ->whereDate('created_at', $today)
            ->get();
    }

    public function createTransaction(array $data)
    {
        $date = Date::parse(Date::Now())->format('Y-m-d');
        $setup = SetupTransaction::query()
                                ->whereDate('closing_date',$date)
                                ->where('status','=',1)
                                ->first();
        if($setup){
            return [
                'status' => false,
                'statusCode' => 500,
                'message' => 'Transaksi hari ini sudah di closing'
            ];
        }
        DB::beginTransaction();
        try{
            $invoiceNumber = $this->invoiceNumber();
            
            // Validasi invoice number
            if (empty($invoiceNumber)) {
                throw new \Exception('Gagal generate invoice number');
            }

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'invoice_number' => $invoiceNumber,
                'transaction_date' => Date::Now(),
                'total_item' => count($data['items']),
                'sub_total' => $data['sub_total'] ?? 0,
                'cashback_earn' => $data['member'] != null || '' ? $data['total'] * 0.02 : 0,
                'grand_total' => $data['total'] ?? 0,
                'paid_amount' => $data['paid_amount'] ?? 0,
                'change_amount' => $data['change'] ?? 0,
                'payment_method' => $data['payment_method'] ?? 'tunai',
                'notes' => $data['notes'] ?? null
            ]);
            // if($transaction){
                // if($data['member']){
                //     $this->syncPointAsync($transaction, $data['member']);
                // }
                $detailTransaction = [];
                foreach($data['items'] as $item){
                    $detailTransaction[] = [
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'qty' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price']
                    ];
                }
                DetailTransaction::insertBatch($detailTransaction);
                $cashback = Cashback::query()->where('member','=',$data['member'])->latest()->first();
                if($data['member']){
                    Cashback::create([
                        'member' => $data['member'],
                        'transaction_id' => $transaction->id,
                        'type' => 'earn',
                        'amount' => $data['total'] * 0.02,
                        'balance_before' => $cashback ? $cashback->balance_after : 0,
                        'balance_after' => $cashback ? $cashback->balance_after + ($data['total'] * 0.02) : $data['total'] * 0.02,
                        'description' => 'Cashback from transaction #'. $transaction->id
                    ]);
                }
            // }
            DB::commit();
            if(!empty($data['member'])) {
                register_shutdown_function(function() use ($transaction, $data) {
                    $this->sendPointToApi($transaction, $data['member']);
                });
            }
            return [
                'status' => true,
                'statusCode' => 201,
                'message' => 'Transaction created',
                'data' => [
                    'id' => $transaction->id,
                    'invoice' => $invoiceNumber,
                    'date' => Date::parse($transaction->transaction_date)->format('d/m/Y H:i'),
                    'total' => $transaction->grand_total,
                    'payment' => $transaction->payment_method,
                    'items' => $data['items']
                ],
            ];
        } catch(\Exception $e){
            DB::rollback();
            return [
                'status' => false,
                'statusCode' => 500,
                'message' => $e->getMessage()
            ];
        }
    }

    public function setupTransaction(array $data)
    {
        $date = Date::Now();
        $setup = SetupTransaction::create([
            'closing_date' => $date,
            'status' => 1,
        ]);
        return [
            'success' => true,
            'statusCode' => 201,
            'message' => 'Closing Transaksi sukses',
            'data' => $setup->closing_date
        ];
    }

    private function sendPointToApi($transaction, $member): void
    {
        // Abaikan kalau koneksi sudah putus
        if (connection_aborted()) {
            return;
        }

        $payload = json_encode([
            'username' => $member,
            'no_transaksi' => $transaction->invoice_number,
            'tgl_transaksi' => $transaction->transaction_date,
            'point_masuk' => $transaction->cashback_earn,
            'point_keluar' => 0,
            'saldo_point' => $transaction->cashback_earn,
            'status' => 'success',
            'description' => $transaction->notes
        ]);

        // cURL dengan timeout pendek
        $ch = curl_init('https://koperasi-stanley.com/api/v1/store/point');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 2,
            CURLOPT_CONNECTTIMEOUT => 1,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        // Log kalau gagal
        if ($httpCode < 200 || $httpCode >= 300) {
            error_log("Point sync failed: HTTP {$httpCode} for transaction {$transaction->id}");
            
            // Queue untuk retry nanti
            $this->queueFailedPointSync($payload, $transaction->id);
        }
    }

    /**
     * Queue point sync yang gagal
     */
    private function queueFailedPointSync(string $payload, int $transactionId): void
    {
        $queueDir = __DIR__ . '/../../storage/queue';
        if (!is_dir($queueDir)) {
            mkdir($queueDir, 0755, true);
        }
        
        file_put_contents(
            $queueDir . '/point_sync_' . $transactionId . '_' . time() . '.json',
            $payload
        );
    }
    private function syncPointAsync($transaction, $member): void
    {
        $payload = json_encode([
            'username' => $member,
            'no_transaksi' => $transaction->invoice_number,
            'tgl_transaksi' => $transaction->transaction_date,
            'point_masuk' => $transaction->cashback_earn,
            'point_keluar' => 0,
            'saldo_point' => $transaction->cashback_earn,
            'status' => 'success',
            'description' => $transaction->notes
        ]);

        $ch = curl_init('https://koperasi-stanley.com/api/v1/store/point');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_TIMEOUT_MS => 500, // 500ms timeout (milidetik)
            CURLOPT_CONNECTTIMEOUT_MS => 300, // 300ms connection timeout
            CURLOPT_NOSIGNAL => 1, // Required untuk timeout milidetik
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_FORBID_REUSE => true,
        ]);
        
        curl_exec($ch);
        curl_close($ch);
    }
}
