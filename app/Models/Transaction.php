<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'by_id',
        'total_price',
        'qty',
        'travel_package_id',
        'travel_package_type_id',
        'notes',
        'payment_status',
        'payment_method',
        'dp_amount',
        'remaining_amount',
        'dp_due_date',
        'addons',
        'remaining_amount',
        'payment_url'
    ];

    protected $casts = [
        'addons' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'by_id', 'id');
    }

    // public function by() {
    //     return $this->belongsTo(User::class, 'by_id', 'id');
    // }

    public function travel_package()
    {
        return $this->belongsTo(TravelPackage::class, 'travel_package_id', 'id');
    }

    public function travel_package_type()
    {
        return $this->belongsTo(PackageType::class, 'travel_package_type_id', 'id');
    }


    public function financial()
    {
        return $this->hasOne(Financial::class, 'id_transaction', 'id');
    }

    public function customer()
    {
        return $this->hasMany(Customer::class, 'transaction_id', 'id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'transaction_id', 'id');
    }

    public function addons(){
        return $this->hasMany(TransactionAddons::class, 'transaction_id', 'id');
    }
}
