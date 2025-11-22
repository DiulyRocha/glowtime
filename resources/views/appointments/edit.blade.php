@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-2xl font-bold text-pink-600 mb-6 text-center">
        ✏️ Editar Agendamento
    </h1>

    <form action="{{ route('appointments.update', $appointment->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Cliente --}}
        <div>
            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
            <select name="client_id" id="client_id" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{ $appointment->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Serviço --}}
        <div>
            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Serviço</label>
            <select name="service_id" id="service_id" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                @foreach ($services as $service)
                    <option value="{{ $service->id }}" {{ $appointment->service_id == $service->id ? 'selected' : '' }}>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Profissional --}}
        <div>
            <label for="professional_id" class="block text-sm font-medium text-gray-700 mb-1">Profissional</label>
            <select name="professional_id" id="professional_id" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                @foreach ($professionals as $professional)
                    <option value="{{ $professional->id }}" {{ $appointment->professional_id == $professional->id ? 'selected' : '' }}>
                        {{ $professional->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Data --}}
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Data</label>
            <input type="date" name="date" id="date" required
                value="{{ $appointment->date }}"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
        </div>

        {{-- Horários --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Início</label>
                <input type="datetime-local" name="start_time" id="start_time" required
                    value="{{ $appointment->start_time }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Término</label>
                <input type="datetime-local" name="end_time" id="end_time" required
                    value="{{ $appointment->end_time }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>
        </div>

        {{-- Valor --}}
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
            <input type="number" step="0.01" min="0" name="price" id="price" required
                value="{{ number_format($appointment->price_cents / 100, 2, '.', '') }}"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
        </div>

        {{-- Status --}}
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" id="status"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                <option value="scheduled" {{ $appointment->status == 'scheduled' ? 'selected' : '' }}>Agendado</option>
                <option value="done" {{ $appointment->status == 'done' ? 'selected' : '' }}>Concluído</option>
                <option value="canceled" {{ $appointment->status == 'canceled' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        {{-- Pagamento --}}
        <div>
            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Pagamento</label>
            <select name="payment_status" id="payment_status"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                <option value="pending" {{ $appointment->payment_status == 'pending' ? 'selected' : '' }}>Pendente</option>
                <option value="paid" {{ $appointment->payment_status == 'paid' ? 'selected' : '' }}>Pago</option>
            </select>
        </div>

        {{-- Botões --}}
        <div class="flex justify-end mt-4">
            <a href="{{ route('reports.appointments') }}"
                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg shadow mr-2 hover:bg-gray-400">
                Cancelar
            </a>
            <button type="submit"
                class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-lg shadow">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>
@endsection
