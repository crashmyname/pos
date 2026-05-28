<?php

namespace App\Import;

use App\Models\Categories;
use Bpjs\Framework\Helpers\Importer;

class CategoryImport extends Importer
{
    // Import logic here
    public function handle(array $mappedRow, int $index): mixed
    {
        $category = Categories::query()->where('name','=',$mappedRow['category'])->first();
        if($category){
            return [
                'row' => $index + 1,
                'status' => 'skipped',
                'category' => $mappedRow['category'] ?? null,
                'message' => 'category sudah ada.'
            ];
        }
        Categories::create([
            'name' => $mappedRow['category'],
            'description' => $mappedRow['description'] ?? null,
        ]);

        return [
            'row' => $index + 1,
            'status' => 'success',
            'category' => $mappedRow['category'] ?? null,
            'message' => 'Berhasil import category.'
        ];
    }
}
