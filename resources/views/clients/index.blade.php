@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $q }}" placeholder="Buscar cliente"
               class="border rounded-2xl px-3 py-2 w-80" />
        <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Buscar</button>
    </form>
    <a href="{{ route('clients.create') }}" class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Novo Cliente</a>
</div>

<div class="bg-white rounded-2xl shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nome</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Aniversário</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $c)
            <tr class="border-t">
                <td class="p-3">{{ $c->name }}</td>
                <td>{{ $c->phone }}</td>
                <td>{{ $c->email }}</td>
                <td>{{ $c->birth_date ? \Carbon\Carbon::parse($c->birth_date)->format('d/m/Y') : '-' }}</td>
                <td class="text-right p-3">
                    <a href="{{ route('clients.edit',$c) }}" class="underline mr-3">Editar</a>
                    <form method="POST" action="{{ route('clients.destroy',$c) }}" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Excluir este cliente?')" class="text-red-600 underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-3">{{ $clients->links() }}</div>
</div>
@endsection
