<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'merchant_id', 'phone', 'sub_total', 'tax_total', 'grand_total'];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function transactionProducts()
    {
        return $this->hasMany(TransactionProduct::class);
    }
}
