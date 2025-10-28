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

    <form method="POST" action="{{ route('settings.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label for="discount" class="block text-sm font-semibold text-gray-600 mb-1">
                Percentual de Desconto de Aniversário (%)
            </label>
            <input type="number" name="discount" id="discount" value="{{ $discount }}"
                   min="0" max="100"
                   class="w-full border rounded px-3 py-2 focus:ring-pink-500 focus:border-pink-500">
            <p class="text-xs text-gray-500 mt-1">
                Esse valor será usado automaticamente na mensagem enviada aos aniversariantes.
            </p>
        </div>

        <button type="submit"
            class="bg-pink-600 hover:bg-pink-500 text-white font-medium px-5 py-2 rounded transition">
            💾 Salvar Alterações
        </button>
    </form>
</div>
@endsection
