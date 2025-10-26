<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * 🔹 Relatório Diário
     */
    public function financeDaily(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $appointments = Appointment::with(['client', 'service', 'professional'])
            ->whereDate('date', $date)
            ->where('payment_status', 'paid')
            ->get();

        $total = $appointments->sum('price_cents') / 100;

        return view('reports.finance_daily', [
            'appointments' => $appointments,
            'total' => number_format($total, 2, ',', '.'),
            'date' => $date,
        ]);
    }

    /**
     * 🔹 Relatório Mensal
     */
   public function financeMonthly(Request $request)
{
    $month = $request->input('month', now()->month);
    $year = $request->input('year', now()->year);

    // 🔹 Filtra apenas agendamentos pagos do mês e ano selecionados
    $appointments = \App\Models\Appointment::whereYear('date', $year)
        ->whereMonth('date', $month)
        ->where('payment_status', 'paid')
        ->with(['client', 'service', 'professional'])
        ->orderBy('date', 'asc')
        ->get();

    // 🔹 Corrige cálculo — SOMA em centavos e só depois divide por 100
    $totalCents = $appointments->sum('price_cents');
    $total = number_format($totalCents / 100, 2, ',', '.');

    // 🔹 Retorna para a view
    return view('reports.finance_monthly', [
        'appointments' => $appointments,
        'total' => $total,
        'month' => $month,
        'year' => $year,
    ]);
}


    /**
     * 🔹 Relatório Anual
     */
    public function financeYearly(Request $request)
    {
        $year = $request->query('year', now()->year);

        $appointments = Appointment::with(['client', 'service', 'professional'])
            ->whereYear('date', $year)
            ->where('payment_status', 'paid')
            ->get();

        $total = $appointments->sum('price_cents') / 100;

        return view('reports.finance_yearly', [
            'appointments' => $appointments,
            'total' => number_format($total, 2, ',', '.'),
            'year' => $year,
        ]);
    }
}
