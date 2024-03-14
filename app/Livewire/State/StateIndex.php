<?php

namespace App\Livewire\State;

use Livewire\Component;
use App\Models\State;
use Livewire\WithPagination;

class StateIndex extends Component
{
    public $search = '';
    public $countryId;
    public $name;
    public $editMode = false;
    public $stateId;

    protected $rules = [
        'countryId' => 'required',
        'name' => 'required',
    ];  

    public function showEditModal($id) {
        $this->reset();
        $this->stateId = $id;
        $this->loadStates();
        $this->editMode = true;
        $this->dispatch('showModal');
    }

    public function loadStates() {
        $state = State::find($this->stateId); 
        $this->countryId = $state->country_id;
        $this->name = $state->name;
    }

    public function deleteState($id) {
        $state = State::find($id);
        $state->delete();
        session()->flash('state-message', 'State Successfully deleted');
    }

    public function showStateModal() {
        $this->reset();
        $this->dispatch('showModal');
    }

    public function closeModal() {
        $this->reset();
        $this->dispatch('closeModal');
    }

    public function storeState() {
        $this->validate();

        State::create([
            'country_id' => $this->countryId,
            'name' => $this->name
        ]);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('state-message', 'State Successfully created');
    }

    public function updateState() {
        $validated = $this->validate([
            'countryId' => 'required',
            'name'        => 'required'
        ]);
        $state = State::find($this->stateId);
        $state->update($validated);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('state-message', 'State Successfully updated');
    }

    public function render()
    {
        $states = State::paginate(5);
        if (strlen($this->search) > 2) {
            $states = State::where('name', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.state.state-index', [
            'states' => $states
        ])->layout('layouts.main');
    }
}
