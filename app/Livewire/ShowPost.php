<?php

namespace App\Livewire;

use App\Models\PackageTerms;
use App\Models\TravelPackage;
use App\Models\Website;
use Livewire\Component;

class ShowPost extends Component
{
    public $TravelPackage;
    public $website;

    public function mount($slug) 
    {
        $this->website = Website::first();
        $this->TravelPackage = TravelPackage::where('slug', $slug)->first();
      
    }
    public function render()
    {
        return view('livewire.show-post');
    }
}
