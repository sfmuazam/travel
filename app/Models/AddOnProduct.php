<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddOnProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'original_price',
        'discount_price',
        'discount_percent',
    ];
}
