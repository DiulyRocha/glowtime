@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $q }}" placeholder="Buscar serviço"
               class="border rounded-2xl px-3 py-2 w-80" />
        <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Buscar</button>
    </form>
    <a href="{{ route('services.create') }}" class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Novo Serviço</a>
</div>

<div class="bg-white rounded-2xl shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nome</th>
                <th>Duração</th>
                <th>Preço</th>
                <th>Ativo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $s)
            <tr class="border-t">
                <td class="p-3">{{ $s->name }}</td>
                <td>{{ $s->duration_minutes }} min</td>
                <td>R$ {{ number_format($s->price_cents / 100, 2, ',', '.') }}</td>
                <td>{{ $s->active ? 'Sim' : 'Não' }}</td>
                <td class="text-right p-3">
                    <a href="{{ route('services.edit',$s) }}" class="underline mr-3">Editar</a>
                    <form method="POST" action="{{ route('services.destroy',$s) }}" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Excluir este serviço?')" class="text-red-600 underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-3">{{ $services->links() }}</div>
</div>
@endsection
