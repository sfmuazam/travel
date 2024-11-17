<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PackageType extends Pivot
{
    use HasFactory;
    protected $table = 'package_types';

    public function package(){
        return $this->belongsTo(TravelPackage::class, 'id_travel_package', 'id');
    }
}
