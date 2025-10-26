@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold text-pink-600 mb-6">
        ⚙️ Configurações do Sistema
    </h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-gray-700 font-semibold mb-1">Desconto de Aniversário (%)</label>
            <input type="number" name="birthday_discount" step="0.1" min="0" max="100"
                value="{{ old('birthday_discount', $discount) }}"
                class="border rounded px-3 py-2 w-32 focus:ring-pink-500 focus:border-pink-500">
            <p class="text-gray-500 text-sm mt-1">
                Defina o percentual de desconto oferecido na mensagem automática de aniversário.
            </p>
        </div>

        <button type="submit" class="bg-pink-600 hover:bg-pink-500 text-white font-medium px-4 py-2 rounded">
            💾 Salvar Configuração
        </button>
    </form>
</div>
@endsection
