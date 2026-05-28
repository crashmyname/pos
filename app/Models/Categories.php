<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class Categories extends BaseModel
{
    // Model logic here
    protected string $table = 'categories';
    protected string $primaryKey = 'id';

    public function products()
    {
        return $this->hasMany(Product::class,'category_id', 'id');
    }
}
