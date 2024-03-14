<?php

namespace App\Livewire\Country;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;

class CountryIndex extends Component
{
    public $search = '';
    public $countryCode;
    public $name;
    public $editMode = false;
    public $countryId;

    protected $rules = [
        'countryCode' => 'required',
        'name' => 'required',
    ];  

    public function showEditModal($id) {
        $this->reset();
        $this->countryId = $id;
        $this->loadCountries();
        $this->editMode = true;
        $this->dispatch('showModal');
    }

    public function loadCountries() {
        $country = Country::find($this->countryId); 
        $this->countryCode = $country->country_code;
        $this->name = $country->name;
    }

    public function deleteCountry($id) {
        $country = Country::find($id);
        $country->delete();
        session()->flash('country-message', 'Country Successfully deleted');
    }

    public function showCountryModal() {
        $this->reset();
        $this->dispatch('showModal');
    }

    public function closeModal() {
        $this->reset();
        $this->dispatch('closeModal');
    }

    public function storeCountry() {
        $this->validate();

        Country::create([
            'country_code' => $this->countryCode,
            'name' => $this->name,
        ]);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('country-message', 'Country Successfully created');
    }

    public function updateCountry() {
        $validated = $this->validate([
            'countryCode' => 'required',
            'name'        => 'required'
        ]);
        $country = Country::find($this->countryId);
        $country->update($validated);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('country-message', 'Country Successfully updated');
    }

    public function render()
    {
        $countries = Country::paginate(5);
        if (strlen($this->search) > 2) {
            $countries = Country::where('name', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.country.country-index', [
            'countries' => $countries
        ])->layout('layouts.main');
    }
}
