<?php

namespace App\Livewire\Locations;

use App\Models\Location;
use Livewire\Component;
use Livewire\WithPagination;

class LocationIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteLocation($id)
    {
        $location = Location::findOrFail($id);
        
        // Alt lokasyonları varsa silmeyi engelle
        if ($location->children()->count() > 0) {
            session()->flash('error', 'Bu lokasyona bağlı alt lokasyonlar bulunduğu için silinemez.');
            return;
        }
        
        $location->delete();
    }

    public function render()
    {
        $locations = Location::with('parent')
            ->where('code', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.locations.location-index', compact('locations'));
    }
}