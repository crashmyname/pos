<?php

namespace App\Services;
use App\Models\Supplier;
use Bpjs\Framework\Helpers\Validator;

class SupplierService
{
    // Service logic here
    public function getData()
    {
        $supplier = Supplier::query()->paginate(10);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'supplier retrieved successfully',
            'data' => $supplier
        ];
    }

    public function createSupplier(array $data)
    {
        $supplier = Supplier::create($data);
        return [
            'status' => true,
            'statusCode' => 201,
            'message' => 'supplier created successfully',
            'data' => $supplier
        ];
    }

    public function updateSupplier(array $data, $id)
    {
        $supplier = Supplier::find($id);
        if(!$supplier){
            return [
                'status' => false,
                'statusCode' => 404,
                'message' => 'supplier not found',
            ];
        }
        $supplier->update($data);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'supplier updated successfully',
            'data' => $supplier
        ];
    }

    public function destroySupplier($id)
    {
        $supplier = Supplier::deleteWhere(['id' => $id]);
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'supplier deleted'
        ];
    }
}
