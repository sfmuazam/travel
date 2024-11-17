<?php

namespace App\Livewire;

use App\Models\TravelPackage;
use App\Models\Website;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Listings extends Component
{
    public $packageTypes;
    public $website;
    public $categories;
    public $selectedCategory;
    public $sortField;
    public $sortDirection;
    public $durationRange;

    public function mount()
    {
        $this->website = Website::first();
        $this->categories = \App\Models\TravelCategory::all();
        $this->selectedCategory = null;
        $this->sortField = 'departure_date';
        $this->sortDirection = 'asc';
        $this->durationRange = null;
        $this->loadPackages();
    }

    public function applyFilters()
    {
        $this->loadPackages();
    }

    public function loadPackages()
    {
        $this->packageTypes = TravelPackage::query()
            ->when($this->selectedCategory, function ($query) {
                $query->where('category_id', $this->selectedCategory);
            })
            ->when($this->durationRange, function ($query) {
                [$minDuration, $maxDuration] = explode('-', $this->durationRange);
                $query->whereRaw('DATEDIFF(arrival_date, departure_date) BETWEEN ? AND ?', [$minDuration, $maxDuration]);
            })
            ->when($this->sortField == 'duration', function ($query) {
                $query->selectRaw('*, DATEDIFF(arrival_date, departure_date) as duration')
                    ->orderBy('duration', $this->sortDirection);
            })
            ->when($this->sortField == 'price', function ($query) {
                $query->leftJoin('package_types', 'travel_packages.id', '=', 'package_types.id_travel_package')
                      ->select('travel_packages.*', DB::raw('IF(package_types.discount_price IS NOT NULL, package_types.discount_price, package_types.price) as effective_price'))
                      ->orderBy('effective_price', $this->sortDirection);
            }, function ($query) {
                if ($this->sortField != 'price') {
                    $query->orderBy($this->sortField, $this->sortDirection);
                }
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.listings');
    }
}
