<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'address',
        'email',
        'phone',
        'major',
        'thumbnail',
        'country_id',
        'state_id',
        'city_id',
        'department_id',
    ];
 
    public function country() {
        return $this->belongsTo(Country::class);
    }


    public function state() {
        return $this->belongsTo(State::class);
    }


    public function city() {
        return $this->belongsTo(City::class);
    }


    public function department() {
        return $this->belongsTo(Department::class);
    }

}
