<?php

namespace App\Livewire;

use App\Models\Branch;
use App\Models\PackageType;
use App\Models\TravelPackage;
use App\Models\Website;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Create Post')]

class IndexPage extends Component
{
    public $packageTypes;
    public $website;

    #[Validate('required')] 
    public $name = '';
    #[Validate('required')] 
    public $address = '';

    public function mount()
    {
        $this->website = Website::first();
        $this->packageTypes = TravelPackage::all();
    }

    public function sendmessage()
    {
        $this->validate(); 

        $name = $this->name;
        $address = $this->address;

        $url = 'https://api.whatsapp.com/send?phone=6281234567&text=Halo%20saya%20' . urlencode($name) . '%20asal%20dari%20' . urlencode($address) . 'ingin berkonsultasi';

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.index-page');
    }
}
