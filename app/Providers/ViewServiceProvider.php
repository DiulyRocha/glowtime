<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Client;
use Carbon\Carbon;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Registra os compositores de view.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $today = now();
            $limit = $today->copy()->addDays(10);

            // Conta aniversariantes dentro dos próximos 10 dias
            $countBirthdays = Client::whereNotNull('birth_date')->get()
                ->filter(function ($client) use ($today, $limit) {
                    $bday = Carbon::parse($client->birth_date);
                    $next = $bday->copy()->year($today->year);

                    // Se o aniversário já passou este ano, soma +1 ano
                    if ($next->lt($today)) {
                        $next->addYear();
                    }

                    return $next->between($today, $limit);
                })
                ->count();

            // Envia o número de aniversariantes para todas as views
            $view->with('countBirthdays', $countBirthdays);
        });
    }
}
