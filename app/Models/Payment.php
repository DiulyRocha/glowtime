<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
protected $fillable = [
'client_id','service_id','professional_id','starts_at','ends_at','status','notes'
];
protected $casts = [ 'starts_at' => 'datetime', 'ends_at' => 'datetime' ];
public function client(){ return $this->belongsTo(Client::class); }
public function service(){ return $this->belongsTo(Service::class); }
public function professional(){ return $this->belongsTo(Professional::class); }
public function payment(){ return $this->hasOne(Payment::class); }
}