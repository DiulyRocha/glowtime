@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <form method="GET" class="flex gap-2">
        <input name="q" value="{{ $q }}" placeholder="Buscar profissional"
               class="border rounded-2xl px-3 py-2 w-80" />
        <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Buscar</button>
    </form>
    <a href="{{ route('professionals.create') }}" class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Novo Profissional</a>
</div>

<div class="bg-white rounded-2xl shadow">
    <table class="w-full text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Nome</th>
                <th>Especialidades</th>
                <th>Telefone</th>
                <th>Email</th>
                <th>Ativo</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($professionals as $p)
            <tr class="border-t">
                <td class="p-3">{{ $p->name }}</td>
                <td>{{ $p->specialties }}</td>
                <td>{{ $p->phone }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->active ? 'Sim' : 'Não' }}</td>
                <td class="text-right p-3">
                    <a href="{{ route('professionals.edit',$p) }}" class="underline mr-3">Editar</a>
                    <form method="POST" action="{{ route('professionals.destroy',$p) }}" class="inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Excluir este profissional?')" class="text-red-600 underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-3">{{ $professionals->links() }}</div>
</div>
@endsection
