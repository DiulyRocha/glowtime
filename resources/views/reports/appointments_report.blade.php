@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6">
    <h1 class="text-2xl font-bold text-pink-600 mb-6 flex items-center gap-2">
        📋 Relatório de Agendamentos
    </h1>

    {{-- 🔹 Resumo rápido --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-pink-100 text-pink-800 p-4 rounded-lg text-center shadow-sm">
            <p class="text-xl font-bold">{{ $summary['total'] }}</p>
            <p class="text-sm uppercase">Total</p>
        </div>
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded-lg text-center shadow-sm">
            <p class="text-xl font-bold">{{ $summary['scheduled'] }}</p>
            <p class="text-sm uppercase">Agendados</p>
        </div>
        <div class="bg-green-100 text-green-800 p-4 rounded-lg text-center shadow-sm">
            <p class="text-xl font-bold">{{ $summary['done'] }}</p>
            <p class="text-sm uppercase">Concluídos</p>
        </div>
        <div class="bg-gray-200 text-gray-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-xl font-bold">{{ $summary['canceled'] }}</p>
            <p class="text-sm uppercase">Cancelados</p>
        </div>
        <div class="bg-emerald-100 text-emerald-700 p-4 rounded-lg text-center shadow-sm">
            <p class="text-xl font-bold">R$ {{ $summary['revenue'] }}</p>
            <p class="text-sm uppercase">Recebido</p>
        </div>
    </div>

    {{-- 🔹 Filtros --}}
    <form method="GET" class="mb-6 flex flex-wrap gap-4">
        <div>
            <label class="text-sm text-gray-600">Data:</label>
            <input type="date" name="date" value="{{ request('date') }}"
                class="border rounded px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
        </div>

        <div>
            <label class="text-sm text-gray-600">Status:</label>
            <select name="status" class="border rounded px-2 py-1 focus:ring-pink-500 focus:border-pink-500">
                <option value="">Todos</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Concluído</option>
                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <button type="submit"
            class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-500 transition">
            Filtrar
        </button>
    </form>

    {{-- 🔹 Tabela de agendamentos --}}
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
                    <th class="p-2 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $a)
                    @php
                        // Tradução dos status e pagamento
                        $statusLabel = match($a->status) {
                            'scheduled' => 'Agendado',
                            'confirmed' => 'Confirmado',
                            'done'      => 'Concluído',
                            'canceled'  => 'Cancelado',
                            default     => ucfirst($a->status),
                        };

                        $paymentLabel = match($a->payment_status) {
                            'paid'    => 'Pago 💰',
                            'pending' => 'Pendente',
                            default   => '',
                        };

                        $bgColor = match(true) {
                            $a->payment_status === 'paid'    => 'bg-green-100 text-green-800',
                            $a->payment_status === 'pending' => 'bg-yellow-100 text-yellow-800',
                            $a->status === 'canceled'        => 'bg-gray-200 text-gray-700',
                            default                          => 'bg-pink-100 text-pink-700',
                        };
                    @endphp

                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">{{ $a->client->name ?? '-' }}</td>
                        <td class="p-2">{{ $a->service->name ?? '-' }}</td>
                        <td class="p-2">{{ $a->professional->name ?? '-' }}</td>
                        <td class="p-2">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
                        <td class="p-2">
                            {{ \Carbon\Carbon::parse($a->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($a->end_time)->format('H:i') }}
                        </td>
                        <td class="p-2 text-right">R$ {{ number_format($a->price_cents / 100, 2, ',', '.') }}</td>
                        <td class="p-2">
                            <span class="px-2 py-1 text-xs rounded font-semibold {{ $bgColor }}">
                                {{ $statusLabel }} — {{ $paymentLabel }}
                            </span>
                        </td>

                        {{-- 🔸 Ações --}}
                        <td class="p-2 text-right space-x-2">
                            {{-- Botão editar --}}
                            <a href="{{ route('appointments.edit', $a->id) }}"
                                class="bg-blue-500 hover:bg-blue-400 text-white px-3 py-1 rounded text-sm">
                                Editar
                            </a>

                            {{-- Botão marcar como pago --}}
                            @if($a->payment_status !== 'paid')
                                <form action="{{ route('appointments.markPaid', $a->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="bg-emerald-500 hover:bg-emerald-400 text-white px-3 py-1 rounded text-sm">
                                        Pago
                                    </button>
                                </form>
                            @endif

                            {{-- Botão excluir --}}
                            <form action="{{ route('appointments.destroy', $a->id) }}" method="POST"
                                  class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="bg-red-500 hover:bg-red-400 text-white px-3 py-1 rounded text-sm delete-btn">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500">
                            Nenhum agendamento encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="mt-4">
        {{ $appointments->links() }}
    </div>
</div>

{{-- 🔹 SweetAlert2 para confirmar exclusão --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const form = this.closest('form');

            Swal.fire({
                title: 'Tem certeza?',
                text: 'Este agendamento será excluído permanentemente!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
