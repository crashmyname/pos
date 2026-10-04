<?php

namespace App\Services;
use App\Models\Cashback;
use App\Models\DetailTransaction;
use App\Models\SetupTransaction;
use App\Models\Transaction;
use Bpjs\Framework\Helpers\Date;
use Bpjs\Framework\Helpers\DB;
use Bpjs\Framework\Helpers\Http\Http;
use Bpjs\Framework\Helpers\Session;
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
            $userId = Session::get('user_id');
        
            // Jika tidak ada di session, coba dari auth()->user()
            if (!$userId) {
                $user = auth()->user();
                if ($user) {
                    $userId = $user->id;
                }
            }
            
            // Jika masih null, throw error
            if (!$userId) {
                throw new \Exception('User tidak ditemukan atau tidak login');
            }
            $invoiceNumber = $this->invoiceNumber();
            
            // Validasi invoice number
            if (empty($invoiceNumber)) {
                throw new \Exception('Gagal generate invoice number');
            }

            $transaction = Transaction::create([
                'user_id' => $userId,
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
            // if(!empty($data['member'])) {
            //     register_shutdown_function(function() use ($transaction, $data) {
            //         $this->sendPointToApi($transaction, $data['member']);
            //     });
            // }
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
        $now = Date::Now();
        $today = Date::parse(Date::Now())->format('Y-m-d');

        $pendingCashbacks = Cashback::query()
        ->where('type', '=', 'EARN')
        ->whereNull('synced_at')
        ->where('sync_attempts', '<', 10)
        ->orderBy('created_at', 'asc')
        ->get();

        $setup = SetupTransaction::create([
            'closing_date' => $now,
            'status' => 1,
        ]);

        $syncResult = [
            'total'   => 0,
            'success' => 0,
            'failed'  => 0,
        ];

        if (!empty($pendingCashbacks) && count($pendingCashbacks) > 0) {
            $syncResult = $this->sendBulkPointToApi($pendingCashbacks);
        }

        return [
            'success' => true,
            'statusCode' => 201,
            'message' => 'Closing Transaksi sukses',
            'data' => $setup->closing_date,
            'sync'       => $syncResult,
        ];
    }

    private function sendBulkPointToApi($cashbacks): array
    {
        if (!is_array($cashbacks)) {
            $cashbacks = method_exists($cashbacks, 'all') ? $cashbacks->all() : (array) $cashbacks;
        }
        $cashbacks = array_values($cashbacks);

        if (empty($cashbacks)) {
            return ['total' => 0, 'success' => 0, 'failed' => 0];
        }

        $apiUrl    = 'https://koperasi-stanley.com/api/v1/store/point';
        $batchSize = 10;
        $timeout   = 15;
        $connect   = 5;

        $result = [
            'total'   => count($cashbacks),
            'success' => 0,
            'failed'  => 0,
        ];

        $get = function ($item, $key, $default = null) {
            if (is_array($item))  return $item[$key]  ?? $default;
            if (is_object($item)) return $item->$key  ?? $default;
            return $default;
        };

        $trxIds = [];
        foreach ($cashbacks as $cb) {
            $tid = $get($cb, 'transaction_id');
            if ($tid !== null) $trxIds[] = $tid;
        }
        $trxIds = array_values(array_unique($trxIds));

        $trxMap = [];
        if (!empty($trxIds)) {
            $trxList = Transaction::query()->whereIn('id', $trxIds)->get();
            if (!is_array($trxList) && method_exists($trxList, 'all')) {
                $trxList = $trxList->all();
            }
            foreach ($trxList as $t) {
                $id = $get($t, 'id');
                if ($id !== null) $trxMap[$id] = $t;
            }
        }

        foreach (array_chunk($cashbacks, $batchSize) as $chunk) {
            $mh      = curl_multi_init();
            $handles = [];

            foreach ($chunk as $cb) {
                $cbId   = $get($cb, 'id');
                $trxId  = $get($cb, 'transaction_id');
                $member = $get($cb, 'member');
                $amount = $get($cb, 'amount');
                $balAft = $get($cb, 'balance_after');
                $descr  = $get($cb, 'description');
                $attmpt = (int) $get($cb, 'sync_attempts', 0);

                $trx = $trxMap[$trxId] ?? null;

                if (!$trx) {
                    $this->markCashbackSyncError(
                        $cbId,
                        'Transaction #' . $trxId . ' not found',
                        $attmpt
                    );
                    $result['failed']++;
                    continue;
                }

                $payload = json_encode([
                    'username'      => $member,
                    'no_transaksi'  => $get($trx, 'invoice_number'),
                    'tgl_transaksi' => $get($trx, 'transaction_date'),
                    'point_masuk'   => $amount,
                    'point_keluar'  => 0,
                    'saldo_point'   => $balAft,
                    'status'        => 'success',
                    'description'   => $descr,
                ]);

                $ch = curl_init($apiUrl);
                curl_setopt_array($ch, [
                    CURLOPT_POST           => true,
                    CURLOPT_POSTFIELDS     => $payload,
                    CURLOPT_HTTPHEADER     => [
                        'Content-Type: application/json',
                        'Accept: application/json',
                    ],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT        => $timeout,
                    CURLOPT_CONNECTTIMEOUT => $connect,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ]);

                curl_multi_add_handle($mh, $ch);
                $handles[(int) $ch] = [
                    'handle'   => $ch,
                    'cb_id'    => $cbId,
                    'attempts' => $attmpt,
                ];
            }

            $running = null;
            do {
                $status = curl_multi_exec($mh, $running);
                if ($running) curl_multi_select($mh, 1.0);
                if ($status !== CURLM_OK) break;
            } while ($running > 0);

            foreach ($handles as $item) {
                $ch       = $item['handle'];
                $cbId     = $item['cb_id'];
                $attempts = $item['attempts'];

                $code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $body   = curl_multi_getcontent($ch);
                $errno  = curl_errno($ch);
                $errmsg = curl_error($ch);

                if ($errno === 0 && $code >= 200 && $code < 300) {
                    $this->markCashbackSynced($cbId, $attempts);
                    $result['success']++;
                } else {
                    $errorText = $errno !== 0
                        ? "cURL err {$errno}: {$errmsg}"
                        : "HTTP {$code}: " . substr((string) $body, 0, 180);

                    $this->markCashbackSyncError($cbId, $errorText, $attempts);
                    $result['failed']++;
                    error_log("[Bulk Sync] cashback #{$cbId} failed: {$errorText}");
                }

                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

            curl_multi_close($mh);
        }

        error_log(sprintf(
            '[Closing Sync] total=%d success=%d failed=%d',
            $result['total'], $result['success'], $result['failed']
        ));

        return $result;
    }

    /**
     * Tandai cashback sukses ter-sync.
     */
    private function markCashbackSynced($cbId, int $attempts): void
    {
        if ($cbId === null) return;

        try {
            $cb = Cashback::query()->where('id', '=', $cbId)->first();

            if (!$cb) {
                error_log("[markSynced] Cashback #{$cbId} not found");
                return;
            }

            $cb->synced_at     = Date::Now();
            $cb->sync_error    = null;
            $cb->sync_attempts = $attempts + 1;

            $cb->save();

        } catch (\Throwable $e) {
            error_log("[markSynced] #{$cbId} failed: " . $e->getMessage());
        }
    }

    /**
     * Tandai cashback gagal (simpan error, attempts +1).
     */
    private function markCashbackSyncError($cbId, string $errorText, int $attempts): void
    {
        if ($cbId === null) return;

        try {
            $cb = Cashback::query()->where('id', '=', $cbId)->first();

            if (!$cb) {
                error_log("[markError] Cashback #{$cbId} not found");
                return;
            }

            $cb->sync_error    = $errorText;
            $cb->sync_attempts = $attempts + 1;

            $cb->save();

        } catch (\Throwable $e) {
            error_log("[markError] #{$cbId} failed: " . $e->getMessage());
        }
    }

    public function retryPendingPointSync(): array
    {
        $pending = Cashback::query()
            ->where('type', '=', 'earn')
            ->whereNull('synced_at')
            ->where('sync_attempts', '<', 10)
            ->orderBy('created_at', 'asc')
            ->limit(500)
            ->get();

        return $this->sendBulkPointToApi($pending);
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
