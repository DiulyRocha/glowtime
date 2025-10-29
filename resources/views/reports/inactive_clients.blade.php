@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold text-pink-600 mb-6 flex items-center gap-2">
        💤 Relatório de Clientes Inativas
    </h1>

    {{-- 🔹 Filtro de período --}}
    <form method="GET" action="{{ route('reports.inactive-clients') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-sm text-gray-600 font-semibold">Período de inatividade:</label>
            <select name="days" class="border rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
                <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 dias</option>
                <option value="60" {{ $days == 60 ? 'selected' : '' }}>60 dias</option>
                <option value="90" {{ $days == 90 ? 'selected' : '' }}>90 dias</option>
                <option value="120" {{ $days == 120 ? 'selected' : '' }}>120 dias</option>
            </select>
        </div>
        <button type="submit"
            class="bg-pink-600 hover:bg-pink-500 text-white font-medium px-4 py-2 rounded transition">
            🔍 Ver
        </button>
    </form>

    {{-- 🔹 Resumo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-pink-100 text-pink-800 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">{{ $list->count() }}</p>
            <p class="text-sm uppercase">CLIENTES INATIVAS</p>
        </div>

        <div class="bg-purple-100 text-purple-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">{{ $days }}</p>
            <p class="text-sm uppercase">DIAS SEM ATENDIMENTO</p>
        </div>

        <div class="bg-gray-100 text-gray-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">{{ $today->format('d/m/Y') }}</p>
            <p class="text-sm uppercase">DATA DA ANÁLISE</p>
        </div>
    </div>

    {{-- 🔹 Tabela principal --}}
    <div class="overflow-x-auto">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">
            📋 Clientes inativas há {{ $days }} dias ou mais
        </h2>

        @if($list->isEmpty())
            <p class="text-gray-500 text-center p-4">Nenhuma cliente inativa encontrada.</p>
        @else
        <table class="w-full border border-gray-200 rounded text-left">
            <thead class="bg-pink-100 text-pink-700">
                <tr>
                    <th class="p-2">Nome</th>
                    <th class="p-2">E-mail</th>
                    <th class="p-2">Telefone</th>
                    <th class="p-2 text-center">Último Atendimento</th>
                    <th class="p-2 text-center">Dias Inativas</th>
                    <th class="p-2 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $c)
                @php
                    $mensagem = str_replace(':name', $c->name, $messageTemplate);
                @endphp
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2 font-medium">{{ $c->name }}</td>
                    <td class="p-2">{{ $c->email }}</td>
                    <td class="p-2">{{ $c->phone }}</td>
                    <td class="p-2 text-center">{{ $c->last_visit ?? 'Nunca veio' }}</td>
                    <td class="p-2 text-center">{{ $c->days_inactive }}</td>
                    <td class="p-2 text-center">
                        <a href="https://wa.me/55{{ preg_replace('/\D/', '', $c->phone) }}?text={{ $mensagem }}"
                           target="_blank"
                           class="bg-green-500 hover:bg-green-600 text-white text-sm px-3 py-1 rounded">
                            💌 Enviar Mensagem
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- 🔹 Rodapé --}}
    <div class="mt-6 flex justify-end gap-4 text-sm">
        <a href="{{ route('reports.birthdays') }}" class="text-pink-600 hover:underline">
            🎂 Ver aniversariantes
        </a>
        <a href="{{ route('reports.finance.monthly') }}" class="text-pink-600 hover:underline">
            💸 Ver relatórios financeiros
        </a>
    </div>

    <p class="text-xs text-gray-500 mt-6">
        * Considera clientes sem registros de atendimento nos últimos {{ $days }} dias.<br>
        * O botão <strong>“Enviar Mensagem”</strong> abre o WhatsApp com mensagem personalizada de campanha de retorno.
    </p>
</div>
@endsection
