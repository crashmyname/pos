<?php

namespace App\Controllers;

use App\Services\TransactionService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class TransactionController extends BaseController
{
    // Controller logic here
    protected $transactionService;
    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public function create(Request $request)
    {
        $transaction = $this->transactionService->createTransaction($request->all());
        return $this->json($transaction,$transaction['statusCode']);
    }
        
    public function setupTransaction(Request $request)
    {
        $setup = $this->transactionService->setupTransaction($request->all());
        return $this->json($setup,$setup['statusCode']);
    }
            
    public function createForApi(Request $request)
    {
        $transaction = $this->transactionService->createTransaction($request->all());
        return $this->json($transaction,$transaction['statusCode']);
        
    }
}
