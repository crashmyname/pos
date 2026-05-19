<?php

namespace App\Controllers;

use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class CashierController extends BaseController
{
    // Controller logic here
    public function index()
    {
        $title = 'Cashier';
        return $this->view('cashier/index',compact('title'),'layouts/cashier');
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
