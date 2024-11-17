<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TravelPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'slug',
        'id_airline',
        'id_catering',
        'id_transportation',
        'id_hotel',
        'departure_date',
        'arrival_date',
        'thumbnail',
        'package_type',
        'package_terms',
        'itinerary',
        'category_id'
    ];

    protected $casts = [
        'package_type' => 'json',
        'package_terms' => 'json',
        'itinerary' => 'json',
        'id_hotel' => 'array',
    ];

    protected static function booted() {
        self::deleting(function (TravelPackage $travelPackage) {
            Storage::disk('public')->delete($travelPackage->thumbnail);
        });
    }

    public function packageType() {
        return $this->hasMany(PackageType::class, 'id_travel_package', 'id');
    }

    public function airline() {
        return $this->belongsTo(Airlines::class, 'id_airline', 'id');
    }

    public function catering() {
        return $this->belongsTo(Catering::class, 'id_catering', 'id');
    }

    public function hotel() {
        return $this->belongsTo(Hotel::class, 'id_hotel', 'id');
    }

    public function transportation() {
        return $this->belongsTo(Transportation::class, 'id_transportation', 'id');
    }

    public function category() {
        return $this->belongsTo(TravelCategory::class, 'category_id', 'id');
    }
}
