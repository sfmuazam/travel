<?php

namespace App\Livewire;

use App\Models\Website;
use Livewire\Component;
use Livewire\WithPagination;

class Gallery extends Component
{
    use WithPagination;

    public $website;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->website = Website::first();
    }

    public function render()
    {
        $galleries = $this->website->fr_gallery;
        $galleriesCollection = collect($galleries)->paginate(12); //Ini siapa yang buat datanya array tapi isinya json woe *_* susah buat paginatenya, tapi ya not bad lah jadinya :'v

        return view('livewire.gallery', [
            'galleries' => $galleriesCollection,
        ]);
    }
}
