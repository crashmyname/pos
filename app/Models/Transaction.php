<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class Transaction extends BaseModel
{
    // Model logic here
    protected string $table = 'transactions';
    protected string $primaryKey = 'id';

    public function details()
    {
        return $this->hasMany(DetailTransaction::class,'transaction_id', 'id');
    }
}
