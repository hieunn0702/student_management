<?php

namespace App\Livewire\Department;

use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;

class DepartmentIndex extends Component
{
    public $search = '';
    public $name;
    public $editMode = false;
    public $departmentId;

    protected $rules = [
        'name' => 'required',
    ];  

    public function showEditModal($id) {
        $this->reset();
        $this->departmentId = $id;
        $this->loadDepartments();
        $this->editMode = true;
        $this->dispatch('showModal');
    }

    public function loadDepartments() {
        $department = $Department::find($this->departmentId);
        $this->name = $department->name;
    }

    public function deleteDepartment($id) {
        $department = $Department::find($id);
        $department->delete();
        session()->flash('department-message', 'Department Successfully deleted');
    }

    public function showDepartmentModal() {
        $this->reset();
        $this->dispatch('showModal');
    }

    public function closeModal() {
        $this->reset();
        $this->dispatch('closeModal');
    }

    public function storeDepartment() {
        $this->validate();

        Department::create([
            'name' => $this->name,
        ]);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('department-message', 'Department Successfully created');
    }

    public function updateDepartment() {  
        $validated = $this->validate([
            'name'        => 'required'
        ]);
        $department = $Department::find($this->departmentId);
        $department->update($validated);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('department-message', 'Department Successfully updated');
    }
    public function render()
    {
        $departments = Department::paginate(5);
        if (strlen($this->search) > 2) {
            $departments = Department::where('name', 'like', "%{$this->search}%")->paginate(5);
        }       
        return view('livewire.department.department-index', [
            'departments' => $departments
        ])->layout('layouts.main');
    }
}
