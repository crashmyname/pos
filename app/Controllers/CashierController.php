<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Services\TransactionService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class CashierController extends BaseController
{
    // Controller logic here
    protected $productService;
    protected $transactionService;
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->transactionService = new TransactionService();
    }
    public function index()
    {
        $title = 'Cashier';
        return $this->view('cashier/index',compact('title'),'layouts/cashier');
    }

    public function getProduct(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $search = $request->input('search',null);
        return $this->json($this->productService->getProduct($perPage,$search),200);
    }

    public function getDailyTransaction()
    {
        return $this->json($this->transactionService->dailyTransaction(),200);
    }

    public function hold()
    {
        return $this->view('cashier/hold',[''],'layouts/cashier');
    }

    public function transaction()
    {
        return $this->view('cashier/transaction',[''],'layouts/cashier');
    }

    public function return()
    {
        return $this->view('cashier/return',[''],'layouts/cashier');
    }
}
