<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InactiveClientsController extends Controller
{
    /**
     * Lista clientes inativas com base em dias sem atendimento.
     */
    public function index(Request $request)
    {
        $today = now();

        // 🔹 Define o limite de dias de inatividade (padrão: 60)
        $days = (int) $request->query('days', 60);
        $limitDate = $today->copy()->subDays($days);

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
            $client->last_visit = $lastVisit->format('d/m/Y H:i');
            $client->days_inactive = $lastVisit->diffInDays(now());

            return $lastVisit->lt($limitDate);
        })->values();

        // 🔹 Mensagem personalizada
        $messageTemplate = "💖 Olá, :name! Faz tempo que não te vemos por aqui! 💅%0A"
            . "A equipe da GlowTime sente sua falta e preparamos uma condição especial para o seu retorno.%0A%0A"
            . "Venha se cuidar e aproveitar momentos de relaxamento e beleza com a gente.%0A"
            . "Agende agora pelo WhatsApp e garanta seu horário! ✨";

        // 🔹 Retorna para a view
        return view('reports.inactive_clients', [
            'list' => $inactiveClients,
            'today' => $today,
            'days' => $days,
            'messageTemplate' => $messageTemplate,
        ]);
    }
}
