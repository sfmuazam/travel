<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductVariant extends Pivot
{
    use SoftDeletes, HasFactory;

    protected $table='product_variants';
    protected $fillable = [
        'product_id',
        'name',
        'thumbnail',
        'custom_price',
        'price',
        'stock',
        'is_discount',
        'discount',
        'active',
        'description'
    ];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // public function setThumbnailAttribute($value) {
    //     $extension = $value->getClientOriginalExtension();
    //     $date = Carbon::now()->format('Y/m/d');

    //     $image = Image::make($value);

    //     $filename = md5($value . time()) . '.' . $extension;

    //     Storage::put('public/images/product-type/' . $date . '/' . $filename, $image->stream());

    //     $this->attributes['thumbnail'] = $date . '/' . $filename;
    // }

    // public function getThumbnailAttribute($value) {
    //     if ($value) {
    //         if(Storage::disk('public')->exists('images/product-type/'.$value)) {
    //             return url('storage/images/product-type/'.$value);
    //         }
    //     }
        
    //     return null;
    // }

    public function scopeActive($query){
        return $query->where('active', 1);
    }
}
