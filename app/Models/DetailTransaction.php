<?php

namespace App\Models;
use Bpjs\Framework\Helpers\BaseModel;

class DetailTransaction extends BaseModel
{
    // Model logic here
    protected string $table = 'transaction_details';
    protected string $primaryKey = 'id';

    public function transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id', 'id');
    }
}
