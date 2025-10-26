<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function via($notifiable)
    {
        return ['mail']; // futuramente podemos incluir 'whatsapp'
    }

    public function toMail($notifiable)
    {
        $a = $this->appointment;

        $data = Carbon::parse($a->date)->format('d/m/Y');
        $hora = Carbon::parse($a->start_time)->format('H:i');

        return (new MailMessage)
            ->subject('💅 Confirmação de Agendamento - GlowTime')
            ->greeting("Olá, {$a->client->name}!")
            ->line("Seu agendamento para *{$a->service->name}* com **{$a->professional->name}** foi confirmado.")
            ->line("📅 Data: {$data}")
            ->line("🕓 Horário: {$hora}")
            ->line("💰 Valor: R$ " . number_format($a->price_cents / 100, 2, ',', '.'))
            ->line('Aguardamos você! 💖')
            ->salutation('Equipe GlowTime');
    }
}
