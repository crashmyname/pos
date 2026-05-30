<?php

namespace App\Services;
use App\Models\Cashback;
use App\Models\DetailTransaction;
use App\Models\Transaction;
use Bpjs\Framework\Helpers\Date;
use Bpjs\Framework\Helpers\DB;
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
        DB::beginTransaction();
        try{
            $invoiceNumber = $this->invoiceNumber();
            
            // Validasi invoice number
            if (empty($invoiceNumber)) {
                throw new \Exception('Gagal generate invoice number');
            }
            
            $transaction = Transaction::create([
                'invoice_number' => $this->invoiceNumber(),
                'transaction_date' => Date::Now(),
                'total_item' => count($data['items']),
                'sub_total' => $data['sub_total'],
                'cashback_earn' => $data['total'] * 0.02,
                'grand_total' => $data['total'],
                'paid_amount' => $data['paid_amount'],
                'change_amount' => $data['change'] ?? 0,
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'] ?? null
            ]);
            if($transaction){
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
            DB::commit();
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
}
