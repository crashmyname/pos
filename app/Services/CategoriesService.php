<?php

namespace App\Services;
use App\Models\Categories;
use Bpjs\Framework\Helpers\Validator;

class CategoriesService
{
    // Service logic here
    public function getData()
    {
        $categories = Categories::query()->paginate(10);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'categories retrieved successfully',
            'data' => $categories
        ];
    }

    public function createCategory(array $data)
    {
        $category = Categories::create($data);
        return [
            'status' => true,
            'statusCode' => 201,
            'message' => 'category created successfully',
            'data' => $category
        ];
    }

    public function updateCategory(array $data, $id)
    {
        $category = Categories::find($id);
        if(!$category){
            return [
                'status' => false,
                'statusCode' => 404,
                'message' => 'category not found',
            ];
        }
        $category->update($data);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'category updated successfully',
            'data' => $category
        ];
    }

    public function destroyCategory($id)
    {
        $category = Categories::deleteWhere(['id' => $id]);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'category deleted'
        ];
    }
}
