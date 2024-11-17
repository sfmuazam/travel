<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manifest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'birthdate',
        'place_of_birth',
        'gender',
        'by_id',
        'middle_name',
        'last_name',
        'father_name',
        'mother_name',

    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'by_id', 'id');
    }
    public function filesuser(){
        return $this->hasOne(FilesUser::class, 'manifest_id', 'id');
    }
    public function customer(){
        return $this->hasOne(Customer::class, 'manifest_id', 'id');
    }
    
}
