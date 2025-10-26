<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Setting;
use Illuminate\Http\Request;
use Carbon\Carbon;



class BirthdayReportsController extends Controller
{
    /**
     * Lista aniversariantes por intervalo (hoje | semana | mês)
     * e gera alertas (próximos 10 dias).
     */
    public function index(Request $request)
    {
        // range pode ser: today | week | month (padrão: today)
        $range = $request->query('range', 'today');

        $today = now()->startOfDay();
        $startOfWeek = now()->startOfWeek();
        $endOfWeek   = now()->endOfWeek();
        $startOfMonth = now()->startOfMonth();
        $endOfMonth   = now()->endOfMonth();

        // 🔹 Busca clientes com data de nascimento registrada
        $clients = Client::select(['id', 'name', 'email', 'phone', 'birth_date'])
            ->whereNotNull('birth_date')
            ->get();

        // 🔹 Helper: calcula a próxima data de aniversário
        $calcNextBirthday = function (Carbon $birthDate) use ($today) {
            $next = $birthDate->copy()->year($today->year);
            if ($next->lt($today)) {
                $next->addYear();
            }
            return $next->startOfDay();
        };

        // 🔹 Filtra conforme o range
        $filtered = match ($range) {
            'today' => $clients->filter(function ($c) use ($today) {
                return $c->birth_date
                    && (int)Carbon::parse($c->birth_date)->format('m') === (int)$today->format('m')
                    && (int)Carbon::parse($c->birth_date)->format('d') === (int)$today->format('d');
            })->values(),

            'week' => $clients->filter(function ($c) use ($startOfWeek, $endOfWeek, $calcNextBirthday) {
                $bday = Carbon::parse($c->birth_date);
                $next = $calcNextBirthday($bday);
                return $next->between($startOfWeek, $endOfWeek);
            })->values(),

            'month' => $clients->filter(function ($c) use ($startOfMonth, $endOfMonth, $calcNextBirthday) {
                $bday = Carbon::parse($c->birth_date);
                $next = $calcNextBirthday($bday);
                return $next->between($startOfMonth, $endOfMonth);
            })->values(),

            default => collect(),
        };

        // 🔹 Ordena os resultados por data de aniversário
        $sorted = $filtered->map(function ($c) use ($calcNextBirthday) {
            $c->next_birthday = $calcNextBirthday(Carbon::parse($c->birth_date));
            $c->age = $c->next_birthday->year - Carbon::parse($c->birth_date)->year;
            return $c;
        })->sortBy('next_birthday')->values();

        // 🔹 Gera alertas (próximos 10 dias)
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

        // 🔹 Retorna para a view
        return view('reports.birthdays', [
            'range'  => $range,
            'today'  => $today,
            'list'   => $sorted,
            'alerts' => $alerts,
        ]);
    }
}
