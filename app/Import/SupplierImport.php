<?php

namespace App\Import;

use App\Models\Supplier;
use Bpjs\Framework\Helpers\Importer;

class SupplierImport extends Importer
{
    // Import logic here
    public function handle(array $mappedRow, int $index): mixed
    {
        $supplier = Supplier::query()->where('name','=',$mappedRow['supplier'])->first();
        if($supplier){
            return [
                'row' => $index + 1,
                'status' => 'skipped',
                'supplier' => $mappedRow['supplier'] ?? null,
                'message' => 'supplier sudah ada.'
            ];
        }
        Supplier::create([
            'name' => $mappedRow['supplier'],
            'phone' => $mappedRow['phone'] ?? null,
            'email' => $mappedRow['email'] ?? null,
            'description' => $mappedRow['description'] ?? null,
        ]);

        return [
            'row' => $index + 1,
            'status' => 'success',
            'supplier' => $mappedRow['supplier'] ?? null,
            'message' => 'Berhasil import supplier.'
        ];
    }
}
