<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    protected $fillable = ['name','specialties','phone','email','active'];
    protected $casts = ['active' => 'boolean'];

    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
}
