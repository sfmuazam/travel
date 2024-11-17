<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Customer extends Pivot
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
      'manifest_id',
      'transaction_id',  
    ];

    public function manifest()
    {
        return $this->belongsTo(Manifest::class, 'manifest_id', 'id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    public function filesuser(){
        return $this->hasOne(FilesUser::class, 'manifest_id', 'id');
    }

}
