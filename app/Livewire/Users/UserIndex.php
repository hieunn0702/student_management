<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $username;
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $userId;
    public $editMode = false;

    protected $rules = [
        'username' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'password' => 'required',
        'email' => 'required|email',
    ];

    public function storeUser() {

        $this->validate();

        User::create([
            'username' => $this->username,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('user-message', 'User Successfully created');
    }

    public function showEditModal($id) {
        $this->reset();
        $this->editMode = true;
        //find user
        $this->userId = $id;
        //load user
        $this->loadUser();
        //show Modal
        $this->dispatch('showModal');
    }

    public function loadUser() {
        $user = User::find($this->userId);
        $this->username = $user->username;
        $this->firstName = $user->first_name;
        $this->lastName = $user->last_name;
        $this->email = $user->email;
    }

    public function updateUser() {
        $validated = $this->validate([
        'username' => 'required',
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email',
        ]);
        $user = User::find($this->userId);
        $user->update($validated);
        $this->reset();
        $this->dispatch('closeModal');
        session()->flash('user-message', 'User Successfully updated');
    }

    public function deleteUser($id) {
        $user = User::find($id);
        $user->delete();

        session()->flash('user-message', 'User Successfully deleted');
    }

    public function closeModal() {
        $this->dispatch('closeModal');
        $this->reset();
    }

    

    public function render()
    {
        $users = User::paginate(5);
        if (strlen($this->search) > 2) {
            $users = User::where('username', 'like', "%{$this->search}%")->paginate(5);
        }
        return view('livewire.users.user-index', [
            'users' => $users
        ])
                    ->layout('layouts.main');
    }
}
