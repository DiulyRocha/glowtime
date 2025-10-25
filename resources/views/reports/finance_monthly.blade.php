@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Relatório Mensal de Receitas</h1>

    <form method="GET" action="{{ route('reports.finance.monthly') }}" class="mb-6 flex items-center gap-3">
        <label class="text-gray-700 font-semibold">Selecione o mês:</label>
        <input type="month" name="month" value="{{ sprintf('%04d-%02d', $year, $month) }}" class="border rounded p-2">
        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white rounded px-4 py-2">
            Ver
        </button>
    </form>

    <p class="text-gray-700 mb-2">
        <strong>🗓 Mês:</strong> 
        {{ \Carbon\Carbon::create()->month($month)->locale('pt_BR')->translatedFormat('F') }} de {{ $year }}
    </p>

    <p class="text-gray-700 mb-4">
        <strong>💰 Total recebido:</strong> 
        R$ {{ number_format($total, 2, ',', '.') }}
    </p>

    <hr class="my-4">

    <p class="text-sm text-gray-500">
        Este relatório considera apenas os agendamentos com status de pagamento 
        <strong class="text-gray-700">"Pago"</strong>.
    </p>

    <div class="mt-6 flex justify-between">
        <a href="{{ route('reports.finance.daily') }}" class="text-pink-600 hover:underline">⬅ Relatório diário</a>
        <a href="{{ route('reports.finance.yearly') }}" class="text-pink-600 hover:underline">➡ Relatório anual</a>
    </div>
</div>
@endsection
