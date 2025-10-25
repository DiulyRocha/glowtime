@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold text-pink-600 mb-6">📋 Relatório de Agendamentos</h1>

    {{-- 🔸 Resumo Rápido --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-pink-100 text-pink-800 p-4 rounded-lg shadow-sm text-center">
            <p class="text-lg font-bold">{{ $summary['total'] }}</p>
            <p class="text-sm uppercase">Total</p>
        </div>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg shadow-sm text-center">
            <p class="text-lg font-bold">{{ $summary['scheduled'] }}</p>
            <p class="text-sm uppercase">Agendados</p>
        </div>
        <div class="bg-green-100 text-green-800 p-4 rounded-lg shadow-sm text-center">
            <p class="text-lg font-bold">{{ $summary['done'] }}</p>
            <p class="text-sm uppercase">Concluídos</p>
        </div>
        <div class="bg-gray-200 text-gray-700 p-4 rounded-lg shadow-sm text-center">
            <p class="text-lg font-bold">{{ $summary['canceled'] }}</p>
            <p class="text-sm uppercase">Cancelados</p>
        </div>
        <div class="bg-emerald-100 text-emerald-700 p-4 rounded-lg shadow-sm text-center">
            <p class="text-lg font-bold">R$ {{ $summary['revenue'] }}</p>
            <p class="text-sm uppercase">Recebido</p>
        </div>
    </div>

    {{-- 🔸 Filtros --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4">
        <div>
            <label class="text-sm text-gray-600">Data:</label>
            <input type="date" name="date" value="{{ request('date') }}" class="border rounded px-2 py-1">
        </div>
        <div>
            <label class="text-sm text-gray-600">Status:</label>
            <select name="status" class="border rounded px-2 py-1">
                <option value="">Todos</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Concluído</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <button class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-500 transition">
            Filtrar
        </button>
    </form>

    {{-- 🔸 Tabela de agendamentos --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-200 rounded">
            <thead class="bg-pink-100 text-pink-700">
                <tr>
                    <th class="p-2">Cliente</th>
                    <th class="p-2">Serviço</th>
                    <th class="p-2">Profissional</th>
                    <th class="p-2">Data</th>
                    <th class="p-2">Horário</th>
                    <th class="p-2 text-right">Valor</th>
                    <th class="p-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $a)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">{{ $a->client->name ?? '-' }}</td>
                        <td class="p-2">{{ $a->service->name ?? '-' }}</td>
                        <td class="p-2">{{ $a->professional->name ?? '-' }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($a->start_time)->format('d/m/Y') }}</td>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($a->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($a->end_time)->format('H:i') }}
                        </td>
                        <td class="p-2 text-right">R$ {{ number_format($a->price_cents / 100, 2, ',', '.') }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 text-xs rounded
                                @if($a->status == 'scheduled') bg-yellow-200 text-yellow-800
                                @elseif($a->status == 'done') bg-green-200 text-green-800
                                @elseif($a->status == 'canceled') bg-gray-300 text-gray-800
                                @else bg-pink-200 text-pink-800 @endif">
                                {{ ucfirst($a->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            Nenhum agendamento encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>
@endsection
