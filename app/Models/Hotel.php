<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'city',
        'country',
        'logo',
        'thumbnail',
        'address',
        'phone_number',
        'email',
        'website',
        'rating',
    ];

    public function rooms()
    {
        return $this->hasMany(RoomHotel::class, 'hotel_id', 'id');
    }
    public function getCountroomAttribute()
    {
        return $this->rooms()->count();
    }
}
