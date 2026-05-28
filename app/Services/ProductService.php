<?php

namespace App\Services;
use App\Import\ProductImport;
use App\Models\Product;
use Bpjs\Framework\Helpers\TablePlus;
use Bpjs\Framework\Helpers\Validator;

class ProductService
{
    // Service logic here
    public function getData($request)
    {
        if(!$request->isAjax()){
            return redirect('admin/category');
        }
        return TablePlus::of('products')
                        ->select('products.name','products.description','products.id','qrcode','buy_price','sell_price','stock_id','image','products.is_active','categories.name as category','suppliers.name as supplier')
                        ->leftJoin('categories','categories.id','=','products.category_id')
                        ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
                        ->searchable([
                            'name',
                            'description',
                            'qrcode',
                            'buy_price',
                            'sell_price'
                        ])
                        ->filters($request->input('filters',[]) ?? [])
                        ->orderBy('id', 'DESC')
                        ->paginate($request->per_page ?? 10, $request->page ?? 1)
                        ->handleDistinct($request->distinct ?? null)
                        ->make();
    }

    public function createProduct(array $data)
    {
        // vd($data['image']);
        $product = Product::create([
            'supplier_id' => $data['supplier_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'qrcode' => $data['qr_code'],
            'name' => $data['product'],
            'description' => $data['description'] ?? null,
            'buy_price' => $data['buy_price'] ?? 0,
            'sell_price' => $data['sell_price'] ?? 0,
            'stock_id' => $data['stock_id'] ?? null,
            'image' => $data['image']['name'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
        ]);
        if($data['image']['error'] == 0){
            $path = storage_path('image/product/');
            if (!is_dir($path)) mkdir($path, 0777, true);
            $file = $data['image']['tmp_name'];
            $filename = $data['image']['name'];
            store($file,$path, $filename);
        }
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
        if($data['image'] && $data['image']['error'] == 4){
            $data['image'] = null;
        }
        $product->update([
            'supplier_id' => $data['supplier_id'] ?? $product->supplier_id,
            'category_id' => $data['category_id'] ?? $product->category_id,
            'qrcode' => $data['qr_code'],
            'name' => $data['product'] ?? $product->name,
            'description' => $data['description'] ?? $product->description,
            'buy_price' => $data['buy_price'] ?? $product->buy_price,
            'sell_price' => $data['sell_price'] ?? $product->sell_price,
            'stock_id' => $data['stock_id'] ?? $product->stock_id,
            'image' => $data['image'] ?? $product->image,
            'is_active' => $data['is_active'] ?? $product->is_active,
        ]);
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
        $path = storage_path('product/');
        if (!is_dir($path)) mkdir($path, 0777, true);
        $file = $data['tmp_name'];
        $filename = uniqid('import_product_') . '.' . $data['extension'];
        $filePath = $path . $filename;
        store($file,$path, $filename);

        $import = new ProductImport($filePath,[
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
