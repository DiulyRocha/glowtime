@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold text-pink-600 mb-6 flex items-center gap-2">
        📅 Relatório Mensal de Receitas
    </h1>

    {{-- 🔹 Filtro por mês e ano --}}
    <form method="GET" action="{{ route('reports.finance.monthly') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-sm text-gray-600 font-semibold">Mês:</label>
            <select name="month" class="border rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-600 font-semibold">Ano:</label>
            <input type="number" name="year" value="{{ $year }}"
                class="border rounded px-3 py-2 w-28 focus:ring-pink-500 focus:border-pink-500">
        </div>
        <button type="submit"
            class="bg-pink-600 hover:bg-pink-500 text-white font-medium px-4 py-2 rounded transition">
            🔍 Ver
        </button>
    </form>

    {{-- 🔹 Resumo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-pink-100 text-pink-800 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">{{ $appointments->count() }}</p>
            <p class="text-sm uppercase">Pagamentos</p>
        </div>
        <div class="bg-emerald-100 text-emerald-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">R$ {{ $total }}</p>
            <p class="text-sm uppercase">Total Recebido</p>
        </div>
        <div class="bg-gray-100 text-gray-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">
                {{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}/{{ $year }}
            </p>
            <p class="text-sm uppercase">Período</p>
        </div>
    </div>

    {{-- 🔹 Tabela --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded text-left">
            <thead class="bg-pink-100 text-pink-700">
                <tr>
                    <th class="p-2">Data</th>
                    <th class="p-2">Cliente</th>
                    <th class="p-2">Serviço</th>
                    <th class="p-2">Profissional</th>
                    <th class="p-2 text-right">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $a)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
                    <td class="p-2">{{ $a->client->name ?? '-' }}</td>
                    <td class="p-2">{{ $a->service->name ?? '-' }}</td>
                    <td class="p-2">{{ $a->professional->name ?? '-' }}</td>
                    <td class="p-2 text-right">
                        R$ {{ number_format($a->price_cents / 100, 2, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        Nenhum pagamento registrado neste mês.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-end gap-4 text-sm">
        <a href="{{ route('reports.finance.daily') }}" class="text-pink-600 hover:underline">
            📆 Ver relatório diário
        </a>
        <a href="{{ route('reports.finance.yearly') }}" class="text-pink-600 hover:underline">
            📊 Ver relatório anual
        </a>
    </div>

    <p class="text-xs text-gray-500 mt-6">
        * Apenas agendamentos com status de pagamento <strong>"Pago"</strong> são considerados.
    </p>
</div>
@endsection
