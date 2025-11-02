@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-pink-600 mb-6 flex items-center gap-2">
        ⚙️ Configurações do Sistema
    </h1>

    {{-- Mensagem de sucesso --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Formulário de atualização --}}
    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 🎂 Desconto de Aniversário --}}
        <div>
            <label for="birthday_discount" class="block text-sm font-semibold text-gray-700 mb-1">
                🎉 Percentual de Desconto de Aniversário (%)
            </label>
            <input type="number" name="birthday_discount" id="birthday_discount"
                   value="{{ $birthday_discount }}"
                   min="0" max="100"
                   class="w-full border rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
            <p class="text-xs text-gray-500 mt-1">
                Esse valor será usado automaticamente nas mensagens enviadas aos aniversariantes.
            </p>
        </div>

        {{-- 💤 Desconto para Clientes Inativas --}}
        <div>
            <label for="inactive_discount" class="block text-sm font-semibold text-gray-700 mb-1">
                💤 Percentual de Desconto para Clientes Inativas (%)
            </label>
            <input type="number" name="inactive_discount" id="inactive_discount"
                   value="{{ $inactive_discount }}"
                   min="0" max="100"
                   class="w-full border rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
            <p class="text-xs text-gray-500 mt-1">
                Esse valor será aplicado nas campanhas automáticas para clientes inativas há 60 dias ou mais.
            </p>
        </div>

        {{-- Botão de salvar --}}
        <div class="pt-4">
            <button type="submit"
                class="bg-pink-600 hover:bg-pink-500 text-white font-medium px-6 py-2 rounded transition">
                💾 Salvar Alterações
            </button>
        </div>
    </form>
</div>
@endsection
