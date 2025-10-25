<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'professional_id',
        'date',
        'start_time',
        'end_time',
        'price_cents',
        'payment_status',
        'status',
        'notes',
    ];

    /**
     * Relação com o cliente
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relação com o serviço
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relação com o profissional
     */
    public function professional()
    {
        return $this->belongsTo(Professional::class);
    }
}
