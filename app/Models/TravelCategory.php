<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelCategory extends Model
{
    use HasFactory;
    protected $table = 'travel_categories';
    protected $fillable = ['name'];

    public function travelPackage(){
        return $this->hasMany(TravelPackage::class, 'category_id', 'id');
    }
}
