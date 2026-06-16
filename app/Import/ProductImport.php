<?php

namespace App\Import;

use App\Models\Categories;
use App\Models\Product;
use App\Models\Supplier;
use Bpjs\Framework\Helpers\Importer;

class ProductImport extends Importer
{
    // Import logic here
    public function handle(array $mappedRow, int $index): mixed
    {
        $product = Product::query()->where('name','=',$mappedRow['product'])->first();
        if($product){
            return [
                'row' => $index + 1,
                'status' => 'skipped',
                'product' => $mappedRow['product'] ?? null,
                'message' => 'product sudah ada.'
            ];
        }
        $category = Categories::query()->where('name','=',$mappedRow['category'])->first();
        $supplier = Supplier::query()->where('name','=',$mappedRow['supplier'])->first();
        Product::create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'qrcode' => $mappedRow['qrcode'],
            'name' => $mappedRow['product'],
            'description' => $mappedRow['description'] ?? null,
            'buy_price' => $mappedRow['buy_price'],
            'sell_price' => $mappedRow['sell_price'],
            'uom' => $mappedRow['uom'],
            'stock_id' => $mappedRow['stock_id'],
            'is_active' => $mappedRow['is_active'],
        ]);

        return [
            'row' => $index + 1,
            'status' => 'success',
            'product' => $mappedRow['product'] ?? null,
            'message' => 'Berhasil import product.'
        ];
    }
}
