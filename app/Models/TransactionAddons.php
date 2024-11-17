<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TransactionAddons extends Pivot
{
    use HasFactory;

    protected $table = 'transaction_addons';
    protected $fillable = [
        'transaction_id',
        'addons_id'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction','id');
    }

    public function addons()
    {
        return $this->belongsTo(AddOnProduct::class, 'addons_id','id');
    }
    
}
