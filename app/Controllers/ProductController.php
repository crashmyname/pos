<?php

namespace App\Controllers;

use App\Services\ProductService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class ProductController extends BaseController
{
    // Controller logic here
    protected $productService;
    public function __construct()
    {
        $this->productService = new ProductService();
    }
    public function index()
    {
        $title = 'Products';
        return $this->view('product/index',compact('title'),'layouts/app');
    }

    public function getData()
    {
        $products = $this->productService->getData();
        return json($products);
    }

    public function create(Request $request)
    {
        $product = $this->productService->createProduct($request->all());
        return json($product);
    }

    public function update(Request $request, $id)
    {
        $product = $this->productService->updateProduct($request->all(), $id);
        return json($product);
    }

    public function destroy($id)
    {
        $product = $this->productService->destroyProduct($id);
        return json($product);
    }
}
