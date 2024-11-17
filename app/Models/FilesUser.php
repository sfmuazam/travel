<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class FilesUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'manifest_id',
        'user_id',
        'bpjs',
        'ktp',
        'passport',
        'file_bpjs',
        'file_ktp',
        'file_passport',
        'file_recommendation_letter',
        'file_family_card',
        'file_pas_photo_4x6',
        'file_marriage_book',
        'file_covid_certificate',
        'passport_expiry_date',
        'family_card',
    ];

    protected static function booted(): void
    {
        self::deleting(function (FilesUser $filesuser) {
            if ($filesuser->file_bpjs) {
                Storage::disk('public')->delete($filesuser->file_bpjs);
            }
            if ($filesuser->file_ktp) {
                Storage::disk('public')->delete($filesuser->file_ktp);
            }
            if ($filesuser->file_visa) {
                Storage::disk('public')->delete($filesuser->file_visa);
            }
            if ($filesuser->file_passport) {
                Storage::disk('public')->delete($filesuser->file_passport);
            }
            if ($filesuser->file_recommendation_letter) {
                Storage::disk('public')->delete($filesuser->file_recommendation_letter);
            }
            if ($filesuser->file_health_certificate) {
                Storage::disk('public')->delete($filesuser->file_health_certificate);
            }
            if ($filesuser->file_family_card) {
                Storage::disk('public')->delete($filesuser->file_family_card);
            }
            if ($filesuser->file_pas_photo_4x6) {
                Storage::disk('public')->delete($filesuser->file_pas_photo_4x6);
            }
            if ($filesuser->file_marriage_book) {
                Storage::disk('public')->delete($filesuser->file_marriage_book);
            }
            if ($filesuser->file_covid_certificate) {
                Storage::disk('public')->delete($filesuser->file_covid_certificate);
            }
        });
        self::deleted(function (FilesUser $filesuser) {
            if ($filesuser->file_bpjs) {
                Storage::disk('public')->delete($filesuser->file_bpjs);
            }
            if ($filesuser->file_ktp) {
                Storage::disk('public')->delete($filesuser->file_ktp);
            }
            if ($filesuser->file_visa) {
                Storage::disk('public')->delete($filesuser->file_visa);
            }
            if ($filesuser->file_passport) {
                Storage::disk('public')->delete($filesuser->file_passport);
            }
            if ($filesuser->file_recommendation_letter) {
                Storage::disk('public')->delete($filesuser->file_recommendation_letter);
            }
            if ($filesuser->file_health_certificate) {
                Storage::disk('public')->delete($filesuser->file_health_certificate);
            }
            if ($filesuser->file_family_card) {
                Storage::disk('public')->delete($filesuser->file_family_card);
            }
            if ($filesuser->file_pas_photo_4x6) {
                Storage::disk('public')->delete($filesuser->file_pas_photo_4x6);
            }
            if ($filesuser->file_marriage_book) {
                Storage::disk('public')->delete($filesuser->file_marriage_book);
            }
            if ($filesuser->file_covid_certificate) {
                Storage::disk('public')->delete($filesuser->file_covid_certificate);
            }
        });
    }
    public function manifest()
    {
        return $this->belongsTo(Manifest::class, 'manifest_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Manifest::class, 'user_id', 'id');
    }

    public function checkfile()
    {
        if ($this->file_ktp || $this->file_health_certificate || $this->file_passport) {
            return true;
        }
    }
}
