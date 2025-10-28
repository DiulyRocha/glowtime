<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BirthdayReportsController extends Controller
{
    /**
     * Exibe o relatório de aniversariantes:
     * - Filtros: hoje | semana | mês (com seleção de mês/ano)
     * - Gera alertas (próximos 10 dias)
     * - Aplica o desconto configurado
     */
    public function index(Request $request)
    {
        // 🔸 Tipo de filtro: today | week | month (padrão: month)
        $range = $request->query('range', 'month');

        // 🔸 Captura o mês e ano selecionados (padrão: mês e ano atuais)
        $selectedMonth = (int) $request->query('month', now()->month);
        $selectedYear  = (int) $request->query('year', now()->year);

        // 🔸 Datas base
        $today        = now()->startOfDay();
        $startOfWeek  = now()->startOfWeek();
        $endOfWeek    = now()->endOfWeek();

        // 🔸 Busca o desconto configurado (padrão = 10%)
        $discountSetting = Setting::where('key', 'birthday_discount')->first();
        $discount = $discountSetting ? (int) $discountSetting->value : 10;

        // 🔸 Busca clientes com data de nascimento válida
        $clients = Client::select(['id', 'name', 'email', 'phone', 'birth_date'])
            ->whereNotNull('birth_date')
            ->get();

        // 🔸 Helper: calcula a próxima data de aniversário
        $calcNextBirthday = function (Carbon $birthDate) use ($today) {
            $next = $birthDate->copy()->year($today->year);
            if ($next->lt($today)) {
                $next->addYear();
            }
            return $next->startOfDay();
        };

        // 🔸 Filtra conforme o intervalo escolhido
        $filtered = match ($range) {
            'today' => $clients->filter(fn($c) =>
                $c->birth_date &&
                (int)Carbon::parse($c->birth_date)->format('m') === (int)$today->format('m') &&
                (int)Carbon::parse($c->birth_date)->format('d') === (int)$today->format('d')
            )->values(),

            'week' => $clients->filter(function ($c) use ($startOfWeek, $endOfWeek, $calcNextBirthday) {
                $bday = Carbon::parse($c->birth_date);
                $next = $calcNextBirthday($bday);
                return $next->between($startOfWeek, $endOfWeek);
            })->values(),

            // ✅ NOVO: Filtro por mês e ano selecionados
            'month' => $clients->filter(function ($c) use ($selectedMonth, $selectedYear) {
                $birth = Carbon::parse($c->birth_date);
                return $birth->month === $selectedMonth;
            })->values(),

            default => collect(),
        };

        // 🔸 Organiza por data e adiciona idade
        $sorted = $filtered->map(function ($c) use ($calcNextBirthday) {
            $c->next_birthday = $calcNextBirthday(Carbon::parse($c->birth_date));
            $c->age = $c->next_birthday->year - Carbon::parse($c->birth_date)->year;
            return $c;
        })->sortBy('next_birthday')->values();

        // 🔸 Gera alertas (próximos 10 dias)
        $alertStart = $today->copy();
        $alertEnd   = $today->copy()->addDays(10);

        $alerts = $clients->map(function ($c) use ($calcNextBirthday) {
            $b = Carbon::parse($c->birth_date);
            $c->next_birthday = $calcNextBirthday($b);
            $c->age = $c->next_birthday->year - $b->year;
            return $c;
        })->filter(function ($c) use ($alertStart, $alertEnd) {
            return $c->next_birthday->between($alertStart, $alertEnd);
        })->sortBy('next_birthday')->values();

        // 🔸 Mensagem personalizada com desconto
        $birthdayMessage = function ($clientName) use ($discount) {
            return "🎉 Olá, {$clientName}! Feliz aniversário! 🎂\n"
                . "A equipe da GlowTime deseja que o seu dia seja repleto de alegria e boas vibrações! 💫\n\n"
                . "Para celebrar com você, preparamos um desconto especial de {$discount}% em qualquer um de nossos serviços, válido até o fim deste mês.\n"
                . "Aproveite o seu momento e venha se cuidar com a gente! 💖";
        };

        // 🔸 Retorna os dados para a view
        return view('reports.birthdays', [
            'range'          => $range,
            'today'          => $today,
            'list'           => $sorted,
            'alerts'         => $alerts,
            'discount'       => $discount,
            'birthdayMessage'=> $birthdayMessage,
            'selectedMonth'  => $selectedMonth,
            'selectedYear'   => $selectedYear,
        ]);
    }
}
