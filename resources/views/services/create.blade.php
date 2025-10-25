@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Novo Serviço</h1>
<form method="POST" action="{{ route('services.store') }}" class="bg-white rounded-2xl shadow p-4 grid gap-3 max-w-xl">
  @csrf
  <input name="name" placeholder="Nome do serviço" class="border rounded-2xl px-3 py-2" required />
  <input name="duration_minutes" type="number" min="5" max="600" placeholder="Duração (minutos)" class="border rounded-2xl px-3 py-2" required />
  <input name="price" placeholder="Preço em R$ (ex: 120,00)" class="border rounded-2xl px-3 py-2" required />
  <label class="flex items-center gap-2">
    <input type="checkbox" name="active" value="1" checked>
    <span>Ativo</span>
  </label>
  <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Salvar</button>
</form>
@endsection
