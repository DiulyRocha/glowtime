<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\BirthdayReportsController;

/*
|--------------------------------------------------------------------------
| 🔓 Rotas Públicas
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
| 🔐 Rotas Protegidas (usuário autenticado)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Painel principal (abre o calendário)
    Route::get('/dashboard', [AppointmentController::class, 'calendar'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | 📅 CALENDÁRIO — FullCalendar
    |--------------------------------------------------------------------------
    */
    Route::get('/appointments/events', [AppointmentController::class, 'events'])->name('appointments.events');
    Route::get('/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');

    /*
    |--------------------------------------------------------------------------
    | 🧾 CRUDs principais
    |--------------------------------------------------------------------------
    */
    Route::resource('clients', ClientController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('professionals', ProfessionalController::class);
    Route::resource('appointments', AppointmentController::class);

    // 💸 Ação rápida — marcar agendamento como pago
    Route::patch('/appointments/{id}/mark-paid', [AppointmentController::class, 'markAsPaid'])
        ->name('appointments.markPaid');

    /*
    |--------------------------------------------------------------------------
    | 💰 Relatórios Financeiros
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports/finance')->group(function () {
        Route::get('/daily', [ReportsController::class, 'financeDaily'])->name('reports.finance.daily');
        Route::get('/monthly', [ReportsController::class, 'financeMonthly'])->name('reports.finance.monthly');
        Route::get('/yearly', [ReportsController::class, 'financeYearly'])->name('reports.finance.yearly');
    });

    /*
    |--------------------------------------------------------------------------
    | 📋 Relatório de Agendamentos
    |--------------------------------------------------------------------------
    */
    Route::get('/reports/appointments', [AppointmentController::class, 'report'])
        ->name('reports.appointments');

    /*
    |--------------------------------------------------------------------------
    | 🎂 Relatório de Aniversários
    |--------------------------------------------------------------------------
    */
    Route::get('/reports/birthdays', [BirthdayReportsController::class, 'index'])
        ->name('reports.birthdays');

    /*
    |--------------------------------------------------------------------------
    | 👤 Perfil do Usuário
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // ⚙️ Configurações do Sistema
    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');



    /*
    |--------------------------------------------------------------------------
    | ✉️ Teste de Envio de E-mail
    |--------------------------------------------------------------------------
    */
    Route::get('/teste-email', function () {
        try {
            \Illuminate\Support\Facades\Mail::raw(
                'Olá! Este é um teste de envio de e-mail via Gmail no GlowTime 💅',
                function ($message) {
                    $message->to('teuemail@gmail.com')
                        ->subject('📩 Teste de Envio de E-mail - GlowTime');
                }
            );
            return '✅ E-mail enviado com sucesso!';
        } catch (\Exception $e) {
            return '❌ Erro ao enviar e-mail: ' . $e->getMessage();
        }
    });
});
