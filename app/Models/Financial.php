<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Financial extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'amount',
        'id_transaction',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id_transaction','id');
    }
}
