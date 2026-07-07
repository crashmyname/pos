<?php

namespace App\Controllers;

use App\Services\ReportService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class ReportController extends BaseController
{
    // Controller logic here
    protected $reportService;
    public function __construct()
    {
        $this->reportService = new ReportService();
    }
    public function report()
    {
        $title = 'Daily Report';
        return $this->view('cashier/report',compact('title'),'layouts/cashier');
    }

    public function getReport(Request $request)
    {
        if(!$request::isAjax()){
            return redirect('report');
        }

        $result = $this->reportService->get();
        return $this->json($result,$result['statusCode']);
    }

    public function getTransactionRecords(Request $request)
    {
        $page    = (int) ($_GET['page']     ?? $request->page     ?? 1);
        $perPage = (int) ($_GET['per_page'] ?? $request->per_page ?? 2);

        $_GET['page']     = $page;
        $_GET['per_page'] = $perPage;

        $result = $this->reportService->getRecord($page,$perPage);
        return $this->json($result,$result['statusCode']);
    }
}
