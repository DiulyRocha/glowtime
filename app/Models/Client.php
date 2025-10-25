<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model {
    protected $fillable = ['name','phone','email','birth_date','notes'];
    public function appointments(){ return $this->hasMany(Appointment::class); }
}
