<?php

namespace App\Controllers;

use App\Services\CategoriesService;
use App\Services\ProductService;
use App\Services\SupplierService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class ProductController extends BaseController
{
    // Controller logic here
    protected $productService;
    protected $categoryService;
    protected $supplierService;
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoriesService();
        $this->supplierService = new SupplierService();
    }
    public function index()
    {
        $title = 'Products';
        $category = $this->categoryService->getCategories();
        $supplier = $this->supplierService->getSuppliers();
        return $this->view('admin/product',compact('title','category','supplier'),'layouts/app');
    }

    public function getData(Request $request)
    {
        $products = $this->productService->getData($request);
        return $this->json($products,$products['statusCode']);
    }

    public function create(Request $request)
    {
        $product = $this->productService->createProduct($request->all());
        return $this->json($product,$product['statusCode']);
    }

    public function update(Request $request, $id)
    {
        $product = $this->productService->updateProduct($request->all(), $id);
        return $this->json($product,$product['statusCode']);
    }

    public function destroy($id)
    {
        $product = $this->productService->destroyProduct($id);
        return $this->json($product,$product['statusCode']);
    }

    public function import(Request $request)
    {
        $product = $this->productService->import($request->file('file'));
        return $this->json($product,$product['statusCode']);
    }
}
