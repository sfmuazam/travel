<?php
 
namespace App\Filament\Admin\Pages;

use Illuminate\Contracts\Support\Htmlable;

class Dashboard extends \Filament\Pages\Dashboard
{
    public function getTitle(): string|Htmlable
    {
        return "Admin Dashboard";
    }
}