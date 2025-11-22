@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg p-8">
    <h1 class="text-3xl font-bold text-pink-600 mb-8 text-center">
        💅 Novo Agendamento
    </h1>

    <form action="{{ route('appointments.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Cliente --}}
        <div>
            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
            <select name="client_id" id="client_id" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                <option value="">Selecione o cliente</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Serviço --}}
        <div>
            <label for="service_id" class="block text-sm font-medium text-gray-700 mb-1">Serviço</label>
            <select name="service_id" id="service_id" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                <option value="">Selecione o serviço</option>
                @foreach ($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Profissional --}}
        <div>
            <label for="professional_id" class="block text-sm font-medium text-gray-700 mb-1">Profissional</label>
            <select name="professional_id" id="professional_id" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
                <option value="">Selecione o profissional</option>
                @foreach ($professionals as $professional)
                    <option value="{{ $professional->id }}">{{ $professional->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Data do Agendamento --}}
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Data do Agendamento</label>
            <input type="date" name="date" id="date" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
        </div>

        {{-- Horários --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Início</label>
                <input type="datetime-local" name="start_time" id="start_time" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Término</label>
                <input type="datetime-local" name="end_time" id="end_time" required
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>
        </div>

        {{-- Valor --}}
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Valor (R$)</label>
            <input type="number" step="0.01" min="0" name="price" id="price"
                placeholder="Ex: 80.00" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-pink-500 focus:border-pink-500">
        </div>

        {{-- Botões --}}
        <div class="flex justify-end pt-4">
            <a href="{{ route('appointments.index') }}"
                class="bg-gray-300 text-gray-700 px-5 py-2 rounded-lg shadow hover:bg-gray-400 transition">
                Cancelar
            </a>
            <button type="submit"
                class="bg-pink-600 hover:bg-pink-500 text-white px-5 py-2 rounded-lg shadow ml-3 transition">
                💖 Salvar Agendamento
            </button>
        </div>
    </form>
</div>
@endsection
