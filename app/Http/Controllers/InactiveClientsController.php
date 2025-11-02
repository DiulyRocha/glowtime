<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InactiveClientsController extends Controller
{
    /**
     * Lista clientes inativas com base em dias sem atendimento
     * e aplica o desconto configurado nas mensagens de retorno.
     */
    public function index(Request $request)
    {
        $today = now();

        // 🔹 Define o limite de dias de inatividade (padrão: 60)
        $days = (int) $request->query('days', 60);
        $limitDate = $today->copy()->subDays($days);

        // 🔹 Busca o percentual de desconto configurado (padrão = 10%)
        $discountSetting = Setting::where('key', 'inactive_discount')->first();
        $discount = $discountSetting ? $discountSetting->value : 10;

        // 🔹 Busca clientes com seus últimos agendamentos
        $clients = Client::select(['id', 'name', 'email', 'phone'])
            ->with(['appointments' => function ($q) {
                $q->orderBy('date', 'desc')
                  ->orderBy('end_time', 'desc');
            }])
            ->get();

        // 🔹 Filtra clientes inativas há mais de X dias
        $inactiveClients = $clients->filter(function ($client) use ($limitDate) {
            $lastAppointment = $client->appointments->first();

            if (!$lastAppointment) {
                // Nunca fez atendimento
                $client->last_visit = null;
                $client->days_inactive = 'Nunca veio';
                return true;
            }

            $lastVisit = Carbon::parse($lastAppointment->date . ' ' . $lastAppointment->end_time);
            $client->last_visit = $lastVisit;
            $client->days_inactive = $lastVisit->diffInDays(now());

            // Retorna TRUE se o cliente está inativo há mais do que o limite
            return $lastVisit->lt($limitDate);
        })->values();

        // 🔹 Template de mensagem com o desconto configurado
        $messageTemplate = "💖 Olá, :name! Faz tempo que não te vemos por aqui! 💅%0A"
            . "A equipe da GlowTime sente sua falta e quer te oferecer um *desconto especial de {$discount}%* em qualquer um de nossos serviços.%0A%0A"
            . "Venha se cuidar e aproveitar momentos de relaxamento e beleza com a gente! ✨%0A"
            . "Agende agora pelo WhatsApp e garanta seu horário! 💖";

        // 🔹 Retorna para a view
        return view('reports.inactive_clients', [
            'list' => $inactiveClients,
            'today' => $today,
            'days' => $days,
            'discount' => $discount,
            'messageTemplate' => $messageTemplate,
        ]);
    }
}
