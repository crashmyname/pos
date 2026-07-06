<?php

namespace App\Services;
use App\Models\Transaction;
use Bpjs\Framework\Helpers\Date;
use Bpjs\Framework\Helpers\Validator;

class ReportService
{
    // Service logic here
    public function get()
    {
        $now = Date::parse(Date::Now())->format('Y-m-d');
        $transaction = Transaction::query()
                                ->select('COUNT(id) as jumlah_transaction','SUM(grand_total) as total_transaction')
                                ->whereDate('transaction_date',$now)
                                // ->groupBy('transaction_date')
                                ->first();
        $paymentSummary = Transaction::query()
                                    ->select('payment_method','COUNT(id) as jumlah_transaction','SUM(grand_total) as total_transaction')
                                    ->whereDate('transaction_date',$now)
                                    ->groupBy(['payment_method'])
                                    // ->groupBy('transaction_date')
                                    ->get();
        $cashierActivity = Transaction::query()
                                    ->with(['users'])
                                    ->select('COUNT(id) as jumlah_transaction','SUM(grand_total) as total_transaction','user_id')
                                    ->whereDate('transaction_date',$now)
                                    ->groupBy(['user_id'])
                                    // ->groupBy('transaction_date')
                                    ->get(\PDO::FETCH_ASSOC, true);
        return [
          'success' => true,
          'statusCode' => 200,
          'message' => 'Data Fetched',
          'data' => [
            'transaction' => $transaction == null ? null : $transaction->toArray(),
            'payment_summary' => $paymentSummary == null ? null : $paymentSummary,
            'cashier_activity' => $cashierActivity == null ? null : Transaction::toCleanArrayCollection($cashierActivity),
          ]
        ];
    }

    public function getRecord($page, $perPage)
    {
        $now = Date::parse(Date::Now())->format('Y-m-d');
        $_GET['page'] = (int) $page;
        $_GET['per_page'] = (int) $perPage;
        $recordTransaction = Transaction::query()
                            ->select('invoice_number','transaction_date','total_item','grand_total','payment_method')
                            ->whereDate('transaction_date',$now)
                            ->orderBy('transaction_date', 'DESC')
                            ->paginate((int)$perPage);
        return [
            'success' => true,
            'statusCode' => 200,
            'data' => $recordTransaction
        ];
    }
}
