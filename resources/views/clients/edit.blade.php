@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Editar Cliente</h1>
<form method="POST" action="{{ route('clients.update',$client) }}" class="bg-white rounded-2xl shadow p-4 grid gap-3 max-w-xl">
    @csrf @method('PUT')
    <input name="name" value="{{ old('name',$client->name) }}" class="border rounded-2xl px-3 py-2" required />
    <input name="email" type="email" value="{{ old('email',$client->email) }}" class="border rounded-2xl px-3 py-2" required />
    <input name="phone" value="{{ old('phone',$client->phone) }}" class="border rounded-2xl px-3 py-2" required />
    <input name="birth_date" type="date" value="{{ old('birth_date',$client->birth_date) }}" class="border rounded-2xl px-3 py-2" />
    <textarea name="notes" class="border rounded-2xl px-3 py-2">{{ old('notes',$client->notes) }}</textarea>
    <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Atualizar</button>
</form>
@endsection