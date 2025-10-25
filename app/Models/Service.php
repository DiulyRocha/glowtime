<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name','duration_minutes','price_cents','active'];
    protected $casts = ['active' => 'boolean'];
    
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
}
