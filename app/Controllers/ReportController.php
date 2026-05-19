<?php

namespace App\Controllers;

use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class ReportController extends BaseController
{
    // Controller logic here
    public function report()
    {
        $title = 'Daily Report';
        return $this->view('cashier/report',compact('title'),'layouts/cashier');
    }
}
