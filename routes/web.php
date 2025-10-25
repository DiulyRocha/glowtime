<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReportsController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Redireciona para o painel (ou login)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Login e Logout
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (somente para usuários logados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | CRUDs principais
    |--------------------------------------------------------------------------
    */
    Route::resource('clients', ClientController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('professionals', ProfessionalController::class);
    Route::resource('appointments', AppointmentController::class);

    /*
    |--------------------------------------------------------------------------
    | Eventos e JSON para o FullCalendar
    |--------------------------------------------------------------------------
    */
    Route::get('/appointments/events', [AppointmentController::class, 'events'])
        ->name('appointments.events');

    /*
    |--------------------------------------------------------------------------
    | Relatórios Financeiros
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports/finance')->group(function () {
        Route::get('/daily', [ReportsController::class, 'financeDaily'])->name('reports.finance.daily');
        Route::get('/monthly', [ReportsController::class, 'financeMonthly'])->name('reports.finance.monthly');
        Route::get('/yearly', [ReportsController::class, 'financeYearly'])->name('reports.finance.yearly');
    });

    /*
    |--------------------------------------------------------------------------
    | Relatório de Agendamentos
    |--------------------------------------------------------------------------
    */
    Route::get('/reports/appointments', [AppointmentController::class, 'report'])
        ->name('reports.appointments');

    /*
    |--------------------------------------------------------------------------
    | Perfil do Usuário
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
