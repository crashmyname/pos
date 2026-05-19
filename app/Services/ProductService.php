<?php

namespace App\Services;
use App\Models\Product;
use Bpjs\Framework\Helpers\Validator;

class ProductService
{
    // Service logic here
    public function getData()
    {
        $products = Product::query()->paginate(10);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ];
    }

    public function createProduct(array $data)
    {
        $product = Product::create($data);
        return [
            'status' => true,
            'statusCode' => 201,
            'message' => 'Product created successfully',
            'data' => $product
        ];
    }

    public function updateProduct(array $data, $id)
    {
        $product = Product::find($id);
        if(!$product){
            return [
                'status' => false,
                'statusCode' => 404,
                'message' => 'Product not found',
            ];
        }
        $product->update($data);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'Product updated successfully',
            'data' => $product
        ];
    }

    public function destroyProduct($id)
    {
        $product = Product::deleteWhere(['id' => $id]);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'Product deleted'
        ];
    }
}
