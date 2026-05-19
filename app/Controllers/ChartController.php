<?php

namespace App\Controllers;

use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class ChartController extends BaseController
{
    // Controller logic here
    public function index(){
        return view('chart/index',[''],'layouts/cashier');
    }

    public function indexlabel()
    {
        return view('chart/label',['']);
    }
}
