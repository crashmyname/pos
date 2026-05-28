<?php

namespace App\Controllers;

use App\Services\CategoriesService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;

class CategoriesController extends BaseController
{
    // Controller logic here
    protected $categoryService;
    public function __construct()
    {
        $this->categoryService = new CategoriesService();
    }
    public function index()
    {
        $title = 'Category';
        return $this->view('admin/category',compact('title'),'layouts/app');
    }

    public function getData(Request $request)
    {
        $category = $this->categoryService->getData($request);
        return $this->json($category,$category['statusCode']);
    }

    public function create(Request $request)
    {
        $category = $this->categoryService->createCategory($request->all());
        return $this->json($category,$category['statusCode']);
    }

    public function update(Request $request, $id)
    {
        $category = $this->categoryService->updateCategory($request->all(), $id);
        return $this->json($category,$category['statusCode']);
    }

    public function destroy($id)
    {
        $category = $this->categoryService->destroyCategory($id);
        return $this->json($category,$category['statusCode']);
    }

    public function import(Request $request)
    {
        $category = $this->categoryService->import($request->file('file'));
        return $this->json($category,$category['statusCode']);
    }
}
