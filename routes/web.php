<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use App\Livewire\Users\UserIndex;
use App\Livewire\Country\CountryIndex;
use App\Livewire\State\StateIndex;
use App\Livewire\City\CityIndex;
use App\Livewire\Department\DepartmentIndex;
use App\Livewire\Student\StudentIndex;

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/countries', CountryIndex::class)->name('countries.index');
    Route::get('/states', StateIndex::class)->name('states.index');
    Route::get('/cities', CityIndex::class)->name('cities.index');
    Route::get('/departments', DepartmentIndex::class)->name('departments.index');
    Route::get('/students', StudentIndex::class)->name('students.index');
});
