<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'logo',
        'favicon',
        'description',
        'subtitle',
        'fr_jumbotron',
        'fr_about',
        'fr_copyright',
        'fr_social_media',
        'fr_jumbotron_title',
        'fr_jumbotron_subtitle',
        'fr_promos',
        'fr_gallery',
        'fr_testimonial',
        'fr_company_advantages',
        'fr_gallery_status',
        'fr_testimonial_status',
        'fr_company_advantages_status',
        'fr_gallery_title',
        'fr_gallery_subtitle',
        'fr_testimonial_title',
        'fr_testimonial_subtitle',
        'fr_company_advantages_title',
        'fr_company_advantages_subtitle',
        'inv_title',
        'inv_logo',
        'inv_email',
        'inv_phone',
        'invoice_bank'
    ];

    protected $casts = [
        'fr_social_media' => 'array',
        'fr_promos' => 'array',
        'fr_gallery' => 'array',
        'fr_company_advantages' => 'array',
        'fr_testimonial' => 'array',
        'invoice_bank' => 'array',
    ];
}
