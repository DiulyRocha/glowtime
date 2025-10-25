@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Cabeçalho -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-2xl font-bold text-pink-600">Agenda de Agendamentos</h1>
        <a href="{{ route('appointments.create') }}" 
           class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-lg shadow transition">
            + Novo Agendamento
        </a>
    </div>

    <!-- Legenda de cores -->
    <div class="flex flex-wrap gap-6 mb-6 text-sm">
        <div class="flex items-center">
            <span class="w-4 h-4 bg-green-500 inline-block rounded mr-2"></span>
            <span>Pago</span>
        </div>
        <div class="flex items-center">
            <span class="w-4 h-4 bg-yellow-400 inline-block rounded mr-2"></span>
            <span>Pendente</span>
        </div>
        <div class="flex items-center">
            <span class="w-4 h-4 bg-gray-400 inline-block rounded mr-2"></span>
            <span>Cancelado</span>
        </div>
    </div>

    <!-- Calendário -->
    <div 
        id="calendar"
        data-events="{{ route('appointments.events') }}" 
        class="bg-white rounded-xl shadow p-4"
        style="min-height: 700px;">
    </div>

{{-- Importa o JS do calendário --}}
@vite(['resources/js/calendar.js'])
@endsection
