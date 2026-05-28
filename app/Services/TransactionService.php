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
        $lastTransaction = Transaction::query()->latest('created_at')
            ->first();
        $lastInvoiceNumber = $lastTransaction ? $lastTransaction->invoice_number : null;
        $currentDate = Date::parse(Date::Now())->format('Ymd');
        if($lastInvoiceNumber && substr($lastInvoiceNumber, 3, 8) === $currentDate){
            $lastSequence = (int) substr($lastInvoiceNumber, 11);
            $newSequence = str_pad($lastSequence + 1, 4, '0',STR_PAD_LEFT);
            return 'INV' . $currentDate . $newSequence;
        }
    }
    public function createTransaction(array $data)
    {
        DB::beginTransaction();
        try{
            $transaction = Transaction::create([
                'invoice_number' => $this->invoiceNumber(),
                'transaction_date' => Date::Now(),
                'total_item' => $data['total_item'],
                'sub_total' => $data['sub_total'],
                'cashback_earn' => $data['total'] * 0.02,
                'grand_total' => $data['total'],
                'paid_amount' => $data['paid_amount'],
                'change_amount' => $data['paid_amount'] - $data['total'],
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
                $cashback = Cashback::query()->where('member','=',$data['member'])->first();
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
                'data' => $transaction->toArray()
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
