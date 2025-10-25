@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-6 text-pink-600">Relatório Anual de Receitas</h1>

    <form method="GET" action="{{ route('reports.finance.yearly') }}" class="mb-6 flex items-center gap-3">
        <label for="year" class="text-gray-700 font-semibold">Selecione o ano:</label>
        <input type="number" id="year" name="year" min="2020" max="2100" value="{{ $year }}" class="border rounded p-2 w-24">
        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white rounded px-4 py-2">
            Ver
        </button>
    </form>

    <p class="text-gray-700 mb-2">
        <strong>📅 Ano:</strong> {{ $year }}
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

    <div class="mt-6 flex justify-start">
        <a href="{{ route('reports.finance.monthly') }}" class="text-pink-600 hover:underline">
            ⬅ Ver relatório mensal
        </a>
    </div>
</div>
@endsection
