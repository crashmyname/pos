<?php

namespace App\Controllers;

use App\Services\SupplierService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class SupplierController extends BaseController
{
    // Controller logic here
    protected $supplierService;
    public function __construct()
    {
        $this->supplierService = new SupplierService();
    }
    public function index()
    {
        $title = 'Suppliers';
        return $this->view('supplier/index',compact('title'),'layouts/app');
    }

    public function getData()
    {
        $supplier = $this->supplierService->getData();
        return json($supplier);
    }

    public function create(Request $request)
    {
        $supplier = $this->supplierService->createSupplier($request->all());
        return json($supplier);
    }

    public function update(Request $request, $id)
    {
        $supplier = $this->supplierService->updateSupplier($request->all(), $id);
        return json($supplier);
    }

    public function destroy($id)
    {
        $supplier = $this->supplierService->destroySupplier($id);
        return json($supplier);
    }
}
