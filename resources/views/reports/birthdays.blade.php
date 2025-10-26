@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold text-pink-600 mb-6 flex items-center gap-2">
        🎂 Relatório de Aniversariantes
    </h1>

    {{-- 🔹 Filtro de período --}}
    <form method="GET" action="{{ route('reports.birthdays') }}" class="mb-6 flex flex-wrap items-end gap-3">
        <div>
            <label class="block text-sm text-gray-600 font-semibold">Período:</label>
            <select name="range" class="border rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
                <option value="today"  {{ $range == 'today' ? 'selected' : '' }}>Hoje</option>
                <option value="week"   {{ $range == 'week' ? 'selected' : '' }}>Esta semana</option>
                <option value="month"  {{ $range == 'month' ? 'selected' : '' }}>Este mês</option>
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
            <p class="text-sm uppercase">Aniversariantes no período</p>
        </div>

        <div class="bg-purple-100 text-purple-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">{{ $alerts->count() }}</p>
            <p class="text-sm uppercase">Alertas (próximos 10 dias)</p>
        </div>

        <div class="bg-gray-100 text-gray-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-lg font-bold">
                {{ $range == 'today' ? 'Hoje' : ($range == 'week' ? 'Semana Atual' : 'Mês Atual') }}
            </p>
            <p class="text-sm uppercase">Período Selecionado</p>
        </div>
    </div>

    {{-- 🔹 Alertas (próximos 10 dias) --}}
    @if($alerts->count() > 0)
        <div class="mb-8">
            <h2 class="text-lg font-semibold text-pink-600 mb-2">🎉 Próximos Aniversários (10 dias)</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-200 rounded text-left">
                    <thead class="bg-pink-100 text-pink-700">
                        <tr>
                            <th class="p-2">Nome</th>
                            <th class="p-2">E-mail</th>
                            <th class="p-2">Telefone</th>
                            <th class="p-2 text-center">Data</th>
                            <th class="p-2 text-center">Idade</th>
                            <th class="p-2 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alerts as $c)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-2 font-medium">{{ $c->name }}</td>
                            <td class="p-2">{{ $c->email }}</td>
                            <td class="p-2">{{ $c->phone }}</td>
                            <td class="p-2 text-center">{{ \Carbon\Carbon::parse($c->birth_date)->format('d/m') }}</td>
                            <td class="p-2 text-center">{{ $c->age }} anos</td>
                            <td class="p-2 text-center">
                                @php
                                    $mensagem = "🎉 Olá, {$c->name}! Feliz aniversário! 🎂%0A".
                                                "A equipe da GlowTime deseja que o seu dia seja repleto de alegria e boas vibrações! 💫%0A%0A".
                                                "Para celebrar com você, preparamos um desconto especial de 10% em qualquer um de nossos serviços, válido até o fim deste mês.%0A".
                                                "Aproveite o seu momento e venha se cuidar com a gente! 💖";
                                @endphp
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
            </div>
        </div>
    @endif

    {{-- 🔹 Tabela principal --}}
    <div class="overflow-x-auto">
        <h2 class="text-lg font-semibold text-gray-700 mb-2">
            @if($range == 'today')
                🎈 Aniversariantes de hoje ({{ $today->format('d/m/Y') }})
            @elseif($range == 'week')
                🎊 Aniversariantes desta semana
            @else
                🎁 Aniversariantes deste mês
            @endif
        </h2>

        @if($list->isEmpty())
            <p class="text-gray-500 text-center p-4">Nenhum aniversariante encontrado neste período.</p>
        @else
        <table class="w-full border border-gray-200 rounded text-left">
            <thead class="bg-pink-100 text-pink-700">
                <tr>
                    <th class="p-2">Nome</th>
                    <th class="p-2">E-mail</th>
                    <th class="p-2">Telefone</th>
                    <th class="p-2 text-center">Data</th>
                    <th class="p-2 text-center">Idade</th>
                    <th class="p-2 text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $c)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-2 font-medium">{{ $c->name }}</td>
                    <td class="p-2">{{ $c->email }}</td>
                    <td class="p-2">{{ $c->phone }}</td>
                    <td class="p-2 text-center">{{ \Carbon\Carbon::parse($c->birth_date)->format('d/m') }}</td>
                    <td class="p-2 text-center">{{ $c->age }} anos</td>
                    <td class="p-2 text-center">
                        @php
                            $mensagem = "🎉 Olá, {$c->name}! Feliz aniversário! 🎂%0A".
                                        "A equipe da GlowTime deseja que o seu dia seja repleto de alegria e boas vibrações! 💫%0A%0A".
                                        "Para celebrar com você, preparamos um desconto especial de 10% em qualquer um de nossos serviços, válido até o fim deste mês.%0A".
                                        "Aproveite o seu momento e venha se cuidar com a gente! 💖";
                        @endphp
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

    <div class="mt-6 flex justify-end gap-4 text-sm">
        <a href="{{ route('reports.finance.monthly') }}" class="text-pink-600 hover:underline">
            💸 Ver relatórios financeiros
        </a>
    </div>

    <p class="text-xs text-gray-500 mt-6">
        * Considera apenas clientes com data de nascimento cadastrada no sistema.  
        * O botão <strong>“Enviar Mensagem”</strong> abre o WhatsApp com uma mensagem personalizada de campanha.
    </p>
</div>
@endsection
