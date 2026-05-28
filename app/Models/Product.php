<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class Product extends BaseModel
{
    // Model logic here
    protected string $table = 'products';
    protected string $primaryKey = 'id';

    public function category()
    {
        return $this->belongsTo(Categories::class,'category_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id', 'id');
    }
}
