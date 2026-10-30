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
    /**
     * Rate cashback — dipakai konsisten di Transaksi dan Cashback
     * supaya nilai yang dikirim ke koperasi selalu sama.
     */
    private const CASHBACK_RATE = 0.01;

    private const KOPERASI_POINT_URL = 'https://koperasi-stanley.com/api/v1/store/point';

    private function invoiceNumber()
    {
        $currentDate = Date::parse(Date::Now())->format('Ymd');

        $lastTransaction = Transaction::query()
            ->where('invoice_number', 'LIKE', "INV{$currentDate}%")
            ->orderBy('invoice_number', 'DESC')
            ->first();

        if ($lastTransaction && $lastTransaction->invoice_number) {
            $lastSequence = (int) substr($lastTransaction->invoice_number, 11);
            $newSequence  = str_pad($lastSequence + 1, 4, '0', STR_PAD_LEFT);
        } else {
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
        $date  = Date::parse(Date::Now())->format('Y-m-d');
        $setup = SetupTransaction::query()
            ->whereDate('closing_date', $date)
            ->where('status', '=', 1)
            ->first();

        if ($setup) {
            return [
                'status'     => false,
                'statusCode' => 500,
                'message'    => 'Transaksi hari ini sudah di closing',
            ];
        }

        DB::beginTransaction();
        try {
            // ==== Resolve user ====
            $userId = Session::get('user_id');
            if (!$userId) {
                $user = auth()->user();
                if ($user) {
                    $userId = $user->id;
                }
            }
            if (!$userId) {
                throw new \Exception('User tidak ditemukan atau tidak login');
            }

            // ==== Invoice ====
            $invoiceNumber = $this->invoiceNumber();
            if (empty($invoiceNumber)) {
                throw new \Exception('Gagal generate invoice number');
            }

            // ==== Normalisasi input ====
            $member = $data['member'] ?? null;
            $total  = (float) ($data['total'] ?? 0);

            $cashbackAmount = !empty($member) ? $total * self::CASHBACK_RATE : 0.0;

            // ==== Simpan transaksi ====
            $transaction = Transaction::create([
                'user_id'          => $userId,
                'invoice_number'   => $invoiceNumber,
                'transaction_date' => Date::Now(),
                'total_item'       => count($data['items']),
                'sub_total'        => $data['sub_total'] ?? 0,
                'cashback_earn'    => $cashbackAmount,
                'grand_total'      => $total,
                'paid_amount'      => $data['paid_amount'] ?? 0,
                'change_amount'    => $data['change'] ?? 0,
                'payment_method'   => $data['payment_method'] ?? 'tunai',
                'notes'            => $data['notes'] ?? null,
            ]);

            // ==== Simpan detail ====
            $detailTransaction = [];
            foreach ($data['items'] as $item) {
                $detailTransaction[] = [
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product_id'],
                    'qty'            => $item['quantity'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['quantity'] * $item['price'],
                ];
            }
            DetailTransaction::insertBatch($detailTransaction);

            // ==== Simpan cashback (kalau member) ====
            $cashbackRecord = null;
            if (!empty($member) && $cashbackAmount > 0) {
                $lastCashback = Cashback::query()
                    ->where('member', '=', $member)
                    ->latest()
                    ->first();

                $balanceBefore = $lastCashback ? (float) $lastCashback->balance_after : 0.0;
                $balanceAfter  = $balanceBefore + $cashbackAmount;

                $cashbackRecord = Cashback::create([
                    'member'         => $member,
                    'transaction_id' => $transaction->id,
                    'type'           => 'earn',
                    'amount'         => $cashbackAmount,
                    'balance_before' => $balanceBefore,
                    'balance_after'  => $balanceAfter,
                    'description'    => 'Cashback from transaction #' . $transaction->id,
                ]);
            }

            DB::commit();

            // ==== Kirim point ke koperasi (realtime) ====
            // Dipanggil SETELAH commit supaya kalau update synced_at gagal,
            // data transaksi tetap tersimpan.
            if ($cashbackRecord) {
                try {
                    $this->sendPointToApi($transaction, $member, $cashbackRecord);
                } catch (\Throwable $e) {
                    error_log('[Realtime Sync] trx #' . $transaction->id . ' error: ' . $e->getMessage());
                }
            }

            return [
                'status'     => true,
                'statusCode' => 201,
                'message'    => 'Transaction created',
                'data'       => [
                    'id'       => $transaction->id,
                    'invoice'  => $invoiceNumber,
                    'date'     => Date::parse($transaction->transaction_date)->format('d/m/Y H:i'),
                    'total'    => $transaction->grand_total,
                    'payment'  => $transaction->payment_method,
                    'items'    => $data['items'],
                ],
            ];
        } catch (\Exception $e) {
            DB::rollback();
            return [
                'status'     => false,
                'statusCode' => 500,
                'message'    => $e->getMessage(),
            ];
        }
    }

    public function setupTransaction(array $data)
    {
        $now = Date::Now();

        $setup = SetupTransaction::create([
            'closing_date' => $now,
            'status'       => 1,
        ]);

        // Fallback bulk sync untuk cashback yang gagal terkirim realtime
        // (opsional — aman untuk diaktifkan karena filter whereNull('synced_at')
        //  akan melewati cashback yang sudah sukses realtime).
        //
        // $pendingCashbacks = Cashback::query()
        //     ->where('type', '=', 'earn')
        //     ->whereNull('synced_at')
        //     ->where('sync_attempts', '<', 10)
        //     ->orderBy('created_at', 'asc')
        //     ->get();
        //
        // $syncResult = ['total' => 0, 'success' => 0, 'failed' => 0];
        // if (!empty($pendingCashbacks) && count($pendingCashbacks) > 0) {
        //     $syncResult = $this->sendBulkPointToApi($pendingCashbacks);
        // }

        return [
            'success'    => true,
            'statusCode' => 201,
            'message'    => 'Closing Transaksi sukses',
            'data'       => $setup->closing_date,
            // 'sync'    => $syncResult,
        ];
    }

    /* =========================================================
     *  BULK SYNC (dipakai untuk retry / fallback / cron)
     * ========================================================= */

    private function sendBulkPointToApi($cashbacks): array
    {
        if (!is_array($cashbacks)) {
            $cashbacks = method_exists($cashbacks, 'all') ? $cashbacks->all() : (array) $cashbacks;
        }
        $cashbacks = array_values($cashbacks);

        if (empty($cashbacks)) {
            return ['total' => 0, 'success' => 0, 'failed' => 0];
        }

        $apiUrl    = self::KOPERASI_POINT_URL;
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
            '[Bulk Sync] total=%d success=%d failed=%d',
            $result['total'], $result['success'], $result['failed']
        ));

        return $result;
    }

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

    /**
     * Panggil dari cron/scheduler untuk retry cashback yang gagal realtime.
     * Contoh: setiap 5 menit.
     */
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

    /* =========================================================
     *  REALTIME SYNC (dipanggil setelah commit)
     * ========================================================= */

    private function sendPointToApi($transaction, $member, $cashback): void
    {
        $payload = json_encode([
            'username'      => $member,
            'no_transaksi'  => $transaction->invoice_number,
            'tgl_transaksi' => $transaction->transaction_date,
            // Nilai harus sama dengan yang tersimpan di Cashback
            'point_masuk'   => $cashback->amount,
            'point_keluar'  => 0,
            'saldo_point'   => $cashback->balance_after,
            'status'        => 'success',
            'description'   => $transaction->notes,
        ]);

        $ch = curl_init(self::KOPERASI_POINT_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $errno    = curl_errno($ch);
        $errmsg   = curl_error($ch);
        curl_close($ch);

        $attempts = ((int) ($cashback->sync_attempts ?? 0)) + 1;

        if ($errno === 0 && $httpCode >= 200 && $httpCode < 300) {
            // ✅ Tandai sukses → tidak akan dikirim ulang oleh retry/bulk
            $cashback->synced_at     = Date::Now();
            $cashback->sync_error    = null;
            $cashback->sync_attempts = $attempts;
            $cashback->save();
        } else {
            $errorText = $errno !== 0
                ? "cURL err {$errno}: {$errmsg}"
                : "HTTP {$httpCode}: " . substr((string) $response, 0, 180);

            // ✅ Simpan error di DB → nanti diambil retryPendingPointSync()
            $cashback->sync_error    = $errorText;
            $cashback->sync_attempts = $attempts;
            $cashback->save();

            error_log("[Realtime Sync] cashback #{$cashback->id} failed: {$errorText}");
        }
    }
}