<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomHotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_type_title',
        'room_number',
        'room_qty',
        'check_in_date',
        'check_out_date',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }
}
