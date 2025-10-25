@extends('layouts.app')

@section('content')
<h1 class="text-xl font-bold mb-4">Agendamentos</h1>

<a href="{{ route('appointments.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded">Novo</a>

<table class="w-full mt-4 bg-white rounded shadow">
  <thead class="bg-gray-100">
    <tr>
      <th class="p-2">Cliente</th>
      <th>Serviço</th>
      <th>Profissional</th>
      <th>Início</th>
      <th>Fim</th>
      <th>Valor</th>
      <th>Status</th>
      <th>Pagamento</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach($appointments as $a)
    <tr class="border-t">
      <td class="p-2">{{ $a->client->name }}</td>
      <td>{{ $a->service->name }}</td>
      <td>{{ $a->professional->name }}</td>
      <td>{{ $a->starts_at->format('d/m H:i') }}</td>
      <td>{{ $a->ends_at->format('H:i') }}</td>
      <td>R$ {{ number_format($a->price_cents/100,2,',','.') }}</td>
      <td>{{ ucfirst($a->status) }}</td>
      <td>{{ $a->payment_status == 'paid' ? 'Pago' : 'Pendente' }}</td>
      <td>
        <form action="{{ route('appointments.cancel',$a) }}" method="POST" class="inline">@csrf @method('PUT')
          <button class="text-red-500">Cancelar</button>
        </form>
        <form action="{{ route('appointments.markPaid',$a) }}" method="POST" class="inline">@csrf @method('PUT')
          <button class="text-green-600">Pagar</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@endsection
