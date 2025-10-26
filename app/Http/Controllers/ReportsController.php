<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * 🔹 Relatório Diário
     */
    public function financeDaily(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        $appointments = Appointment::with(['client', 'service', 'professional'])
            ->whereDate('date', $date)
            ->where('payment_status', 'paid')
            ->orderBy('start_time')
            ->get();

        $totalCents = $appointments->sum('price_cents');
        $total = number_format($totalCents / 100, 2, ',', '.');

        return view('reports.finance_daily', [
            'appointments' => $appointments,
            'date' => $date,
            'total' => $total,
        ]);
    }

    /**
     * 🔹 Relatório Mensal
     */
    public function financeMonthly(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $appointments = Appointment::with(['client', 'service', 'professional'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->where('payment_status', 'paid')
            ->orderBy('date', 'asc')
            ->get();

        // 🔸 Soma total em centavos e formata corretamente
        $totalCents = $appointments->sum('price_cents');
        $total = number_format($totalCents / 100, 2, ',', '.');

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

        $totalCents = $appointments->sum('price_cents');
        $total = number_format($totalCents / 100, 2, ',', '.');

        return view('reports.finance_yearly', [
            'appointments' => $appointments,
            'total' => $total,
            'year' => $year,
        ]);
    }
}
