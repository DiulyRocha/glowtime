@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Novo Cliente</h1>
<form method="POST" action="{{ route('clients.store') }}" class="bg-white rounded-2xl shadow p-4 grid gap-3 max-w-xl">
@csrf
<input name="name" placeholder="Nome" class="border rounded-2xl px-3 py-2" required />
<input name="email" type="email" placeholder="E-mail" class="border rounded-2xl px-3 py-2" required />
<input name="phone" placeholder="Telefone" class="border rounded-2xl px-3 py-2" required />
<input name="birth_date" type="date" class="border rounded-2xl px-3 py-2" />
<textarea name="notes" placeholder="Observações" class="border rounded-2xl px-3 py-2"></textarea>
<button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Salvar</button>
</form>
@endsection