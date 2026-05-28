<?php

namespace App\Services;
use App\Import\CategoryImport;
use App\Models\Categories;
use Bpjs\Framework\Helpers\TablePlus;
use Bpjs\Framework\Helpers\Validator;

class CategoriesService
{
    // Service logic here
    public function getCategories()
    {
        return Categories::query()->select('name','description','id')->get();
    }
    public function getData($request)
    {
        if(!$request->isAjax()){
            return redirect('admin/category');
        }
        return TablePlus::of('categories')
                        ->select('name','description','id')
                        ->searchable([
                            'name',
                            'description'
                        ])
                        ->filters($request->input('filters',[]) ?? [])
                        ->orderBy('id', 'DESC')
                        ->paginate($request->per_page ?? 10, $request->page ?? 1)
                        ->handleDistinct($request->distinct ?? null)
                        ->make();
    }

    public function createCategory(array $data)
    {
        $cek = Categories::query()->where('name','=',$data['category'])->exists();
        if($cek){
            return [
                'status' => false,
                'statusCode' => 422,
                'message' => 'Category already exists'
            ];
        }
        $category = Categories::create([
            'name' => $data['category'],
            'description' => $data['description'] ?? null
        ]);
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
        $category->update([
            'name' => $data['category'] ?? $category->name,
            'description' => $data['description'] ?? $category->description
        ]);
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
        $path = storage_path('category/');
        if (!is_dir($path)) mkdir($path, 0777, true);
        $file = $data['tmp_name'];
        $filename = uniqid('import_category_') . '.' . $data['extension'];
        $filePath = $path . $filename;
        store($file,$path, $filename);

        $import = new CategoryImport($filePath,[
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
