<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class Product extends BaseModel
{
    // Model logic here
    protected string $table = 'products';
    protected string $primaryKey = 'id';
}
