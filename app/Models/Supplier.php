<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class Supplier extends BaseModel
{
    // Model logic here
    protected string $table = 'suppliers';
    protected string $primaryKey = 'id';

    public function products()
    {
        return $this->hasMany(Product::class,'supplier_id', 'id');
    }
}
