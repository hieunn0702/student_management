<?php

namespace App\Livewire\Student;

use Livewire\Component;
use App\Models\Student;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class StudentIndex extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $firstName;
    public $lastName;
    public $address;
    public $email;
    public $phone;
    public $major;
    public $thumbnail;
    public $countryId;
    public $stateId;
    public $cityId;
    public $departmentId;
    public $editMode = false;
    public $studentId;

    public $selectedDepartmentId = null;

    protected $rules = [
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'thumbnail' => 'required',
            'countryId' => 'required',
            'stateId' => 'required',
            'cityId' => 'required',
            'departmentId' => 'required',
    ];  

    public function showEditModal($id) {
        $this->reset();
        $this->studentId = $id;
        $this->loadStudents();
        $this->editMode = true;
        $this->dispatch('showModal');
    }

    public function loadStudents() {
        $student = Student::find($this->studentId); 
        $this->firstName = $student->first_name;
        $this->lastName = $student->last_name;
        $this->address = $student->address;
        $this->email = $student->email;
        $this->phone = $student->phone;
        $this->thumbnail = $student->thumbnail;
        $this->countryId = $student->country_id;
        $this->stateId = $student->state_id;
        $this->cityId = $student->cityId;
        $this->departmentId = $student->departmentId;
    }

    public function deleteStudent($id) {
        $student = Student::find($id);
        $student->delete();
        session()->flash('student-message', 'Student Successfully deleted');
    }

    public function showStudentModal() {
        $this->reset();
        $this->dispatch('showModal');
    }

    public function closeModal() {
        $this->reset();
        $this->dispatch('closeModal');
    }

    public function storeStudent() {
        $this->validate();

        Student::create([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'thumbnail' => $this->thumbnail,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'department_id' => $this->departmentId,
        ]);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('student-message', 'Student Successfully created');
    }

    public function updateStudent() {
        $this->validate();
        $student = Student::find($this->studentId);
        $student->update([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'thumbnail' => $this->thumbnail,
            'country_id' => $this->countryId,
            'state_id' => $this->stateId,
            'city_id' => $this->cityId,
            'department_id' => $this->departmentId,
        ]);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('student-message', 'Student Successfully updated');
    }
    public function render()
    {
        $students = Student::paginate(5);
        if (strlen($this->search) > 2) {
            if($this->selectedDepartmentId) {
                $students = Student::where('first_name', 'like', "%{$this->search}%")
                                    ->where('department_id', $this->selectedDepartmentId)
                                    ->paginate(5);
            } else {
                $students = Student::where('first_name', 'like', "%{$this->search}%")->paginate(5);
            }
        }
        elseif($this->selectedDepartmentId) {
            $students = Student::where('department_id', $this->selectedDepartmentId)->paginate(5);
        }
        return view('livewire.student.student-index', ['students' => $students])->layout('layouts.main');
    }
}
