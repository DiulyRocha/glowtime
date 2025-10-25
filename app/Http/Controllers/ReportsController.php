<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Relatório Diário
     */
    public function financeDaily(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        $total = Appointment::whereDate('start_time', $date)
            ->where('payment_status', 'paid')
            ->sum('price_cents');

        return view('reports.finance_daily', [
            'total' => $total / 100,
            'date'  => $date,
        ]);
    }

    /**
     * Relatório Mensal
     */
    public function financeMonthly(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year  = $request->query('year', now()->year);

        $total = Appointment::whereYear('start_time', $year)
            ->whereMonth('start_time', $month)
            ->where('payment_status', 'paid')
            ->sum('price_cents');

        return view('reports.finance_monthly', [
            'total' => $total / 100,
            'month' => $month,
            'year'  => $year,
        ]);
    }

    /**
     * Relatório Anual
     */
    public function financeYearly(Request $request)
    {
        $year = $request->query('year', now()->year);

        $total = Appointment::whereYear('start_time', $year)
            ->where('payment_status', 'paid')
            ->sum('price_cents');

        return view('reports.finance_yearly', [
            'total' => $total / 100,
            'year'  => $year,
        ]);
    }
}
