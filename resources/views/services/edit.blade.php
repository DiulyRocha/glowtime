@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Editar Serviço</h1>
<form method="POST" action="{{ route('services.update',$service) }}" class="bg-white rounded-2xl shadow p-4 grid gap-3 max-w-xl">
  @csrf @method('PUT')
  <input name="name" value="{{ old('name',$service->name) }}" class="border rounded-2xl px-3 py-2" required />
  <input name="duration_minutes" type="number" min="5" max="600" value="{{ old('duration_minutes',$service->duration_minutes) }}" class="border rounded-2xl px-3 py-2" required />
  <input name="price" value="{{ old('price', number_format($service->price_cents/100, 2, ',', '.')) }}" class="border rounded-2xl px-3 py-2" required />
  <label class="flex items-center gap-2">
    <input type="checkbox" name="active" value="1" {{ old('active',$service->active) ? 'checked' : '' }}>
    <span>Ativo</span>
  </label>
  <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Atualizar</button>
</form>
@endsection
