<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class Cashback extends BaseModel
{
    // Model logic here
    protected string $table = 'cashback_histories';
    protected string $primaryKey = 'id';
}
