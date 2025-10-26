<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    view()->composer('*', function ($view) {
        try {
            // busca clientes com aniversário nos próximos 10 dias
            $clients = \App\Models\Client::whereNotNull('birthdate')->get();

            $today = now();
            $alerts = $clients->filter(function ($c) use ($today) {
                $bday = \Carbon\Carbon::parse($c->birthdate);
                $next = $bday->copy()->year($today->year);
                if ($next->lt($today)) {
                    $next->addYear();
                }
                return $next->between($today, $today->copy()->addDays(10));
            });

            // conta quantos aniversariantes tem nos próximos 10 dias
            $birthdayAlertsCount = $alerts->count();
        } catch (\Exception $e) {
            $birthdayAlertsCount = 0;
        }

        $view->with('birthdayAlertsCount', $birthdayAlertsCount);
    });
}

}
