<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id', 'expires_at', 'status'];

    public $timestamps = true;

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }

    /**
     * Boot the model and set up the creating event.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->expires_at = now()->addHours(5);
        });

        static::created(function ($invoice) {
            $invoice->invoice_id = 'INV-' . str_pad($invoice->id, 9, '0', STR_PAD_LEFT);
            $invoice->save();
        });
    }
}
