<?php

namespace App\Services;
use App\Models\Supplier;
use App\Import\SupplierImport;
use Bpjs\Framework\Helpers\TablePlus;
use Bpjs\Framework\Helpers\Validator;

class SupplierService
{
    // Service logic here
    public function getSuppliers()
    {
        return Supplier::query()->select('name','description','id')->get();
    }
    public function getData($request)
    {
        if(!$request->isAjax()){
            return redirect('admin/category');
        }
        return TablePlus::of('suppliers')
                        ->select('name','description','phone','email','id')
                        ->searchable([
                            'name',
                            'description',
                            'phone',
                            'email'
                        ])
                        ->filters($request->input('filters',[]) ?? [])
                        ->orderBy('id', 'DESC')
                        ->paginate($request->per_page ?? 10, $request->page ?? 1)
                        ->handleDistinct($request->distinct ?? null)
                        ->make();
    }

    public function createSupplier(array $data)
    {
        $cek = Supplier::query()->where('name','=',$data['supplier'])->exists();
        if($cek){
            return [
                'status' => false,
                'statusCode' => 422,
                'message' => 'Supplier already exists'
            ];
        }
        $supplier = Supplier::create([
            'name' => $data['supplier'],
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'description' => $data['description'] ?? null,
        ]);
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
        $supplier->update([
            'name' => $data['supplier'] ?? $supplier->name,
            'phone' => $data['phone'] ?? $supplier->phone,
            'email' => $data['email'] ?? $supplier->email,
            'description' => $data['description'] ?? $supplier->description
        ]);
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

    public function import($data)
    {
        if ($data['error'] == 4) {
            return [
                'status' => false,
                'statusCode' => 400, 
                'message' => 'File tidak ada'
            ];
        }
        $validateType = $data['mime_type'];
        $allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        if($data && !in_array($validateType,$allowedTypes)){
            $errors = ['file' => ['File must be a valid excel file']];
        }
        if(!empty($errors)){
            return [
                'status'=>false,
                'statusCode'=>422,
                'message'=>$errors
            ];
        }
        $path = storage_path('supplier/');
        if (!is_dir($path)) mkdir($path, 0777, true);
        $file = $data['tmp_name'];
        $filename = uniqid('import_supplier_') . '.' . $data['extension'];
        $filePath = $path . $filename;
        store($file,$path, $filename);

        $import = new SupplierImport($filePath,[
            'hasHeader' => true,
            'sheetName' => 'Sheet1'
        ]);
        $results = $import->import();
        return [
            'status' => true,
            'statusCode' => 200,
            'message' => 'Import selesai',
            'results' => $results,
        ];
    }
}
