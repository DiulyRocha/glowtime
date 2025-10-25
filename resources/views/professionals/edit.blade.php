@extends('layouts.app')
@section('content')
<h1 class="text-xl font-bold mb-4">Editar Profissional</h1>
<form method="POST" action="{{ route('professionals.update',$professional) }}" class="bg-white rounded-2xl shadow p-4 grid gap-3 max-w-xl">
  @csrf @method('PUT')
  <input name="name" value="{{ old('name',$professional->name) }}" class="border rounded-2xl px-3 py-2" required />
  <input name="specialties" value="{{ old('specialties',$professional->specialties) }}" class="border rounded-2xl px-3 py-2" />
  <input name="phone" value="{{ old('phone',$professional->phone) }}" class="border rounded-2xl px-3 py-2" />
  <input name="email" type="email" value="{{ old('email',$professional->email) }}" class="border rounded-2xl px-3 py-2" />
  <label class="flex items-center gap-2">
    <input type="checkbox" name="active" value="1" {{ old('active',$professional->active) ? 'checked' : '' }}>
    <span>Ativa</span>
  </label>
  <button class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-2xl">Atualizar</button>
</form>
@endsection
