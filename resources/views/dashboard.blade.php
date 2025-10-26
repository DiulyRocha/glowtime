@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <!-- Cabeçalho -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <h1 class="text-2xl font-bold text-pink-600">Agenda de Agendamentos</h1>
        <a href="{{ route('appointments.create') }}" 
           class="bg-pink-600 hover:bg-pink-500 text-white px-4 py-2 rounded-lg shadow transition">
            + Novo Agendamento
        </a>
    </div>

    <!-- Legenda -->
    <div class="flex flex-wrap gap-6 mb-6 text-sm">
        <div class="flex items-center"><span class="w-4 h-4 bg-green-500 inline-block rounded mr-2"></span> Pago</div>
        <div class="flex items-center"><span class="w-4 h-4 bg-yellow-400 inline-block rounded mr-2"></span> Pendente</div>
        <div class="flex items-center"><span class="w-4 h-4 bg-gray-400 inline-block rounded mr-2"></span> Cancelado</div>
    </div>

    <!-- Calendário -->
    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <div id="calendar"></div>
    </div>
</div>

{{-- 🔹 Bibliotecas --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/locales/pt-br.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'pt-br', // 🇧🇷 idioma definido
        initialView: 'dayGridMonth',
        height: 'auto',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        buttonText: {
            today: 'Hoje',
            month: 'Mês',
            week: 'Semana',
            day: 'Dia'
        },
        events: '{{ route('appointments.events') }}',

        // 🔹 Criar novo agendamento com selects reais
        dateClick: function(info) {
            Swal.fire({
                title: 'Novo Agendamento',
                html: `
                    <div style="text-align:left">
                        <label>Cliente:</label>
                        <select id="client_id" class="swal2-select">
                            <option value="">Selecione</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>

                        <label>Serviço:</label>
                        <select id="service_id" class="swal2-select">
                            <option value="">Selecione</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                            @endforeach
                        </select>

                        <label>Profissional:</label>
                        <select id="professional_id" class="swal2-select">
                            <option value="">Selecione</option>
                            @foreach ($professionals as $professional)
                                <option value="{{ $professional->id }}">{{ $professional->name }}</option>
                            @endforeach
                        </select>

                        <label>Horário de Início:</label>
                        <input id="start_time" type="time" class="swal2-input">

                        <label>Horário de Término:</label>
                        <input id="end_time" type="time" class="swal2-input">

                        <label>Valor (R$):</label>
                        <input id="price" type="number" class="swal2-input" min="0" step="0.01" placeholder="Ex: 50.00">
                    </div>
                `,
                confirmButtonText: 'Salvar',
                showCancelButton: true,
                confirmButtonColor: '#d63384',
                cancelButtonText: 'Cancelar',
                preConfirm: () => {
                    const data = {
                        client_id: document.getElementById('client_id').value,
                        service_id: document.getElementById('service_id').value,
                        professional_id: document.getElementById('professional_id').value,
                        start_time: document.getElementById('start_time').value,
                        end_time: document.getElementById('end_time').value,
                        price: document.getElementById('price').value
                    };

                    if (!data.client_id || !data.service_id || !data.professional_id || !data.start_time || !data.end_time) {
                        Swal.showValidationMessage('Preencha todos os campos obrigatórios!');
                        return false;
                    }

                    return data;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const data = result.value;
                    fetch(`/appointments`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ...data,
                            date: info.dateStr
                        })
                    }).then(r => {
                        if (r.ok) {
                            Swal.fire('Agendamento criado com sucesso!', '', 'success');
                            calendar.refetchEvents();
                        } else {
                            Swal.fire('Erro!', 'Não foi possível salvar o agendamento.', 'error');
                        }
                    });
                }
            });
        },

        // 🔹 Clique em evento existente
        eventClick: function(info) {
            const event = info.event;
            const props = event.extendedProps;

            Swal.fire({
                title: `<strong>${event.title}</strong>`,
                html: `
                    <div style="text-align:left; font-size:15px;">
                        <p><b>Profissional:</b> ${props.profissional}</p>
                        <p><b>Valor:</b> R$ ${Number(props.valor).toFixed(2)}</p>
                        <p><b>Status:</b> ${props.status === 'done' ? 'Concluído' : props.status === 'scheduled' ? 'Agendado' : 'Cancelado'}</p>
                        <p><b>Pagamento:</b> ${props.payment === 'paid' ? 'Pago' : 'Pendente'}</p>
                        <p><b>Início:</b> ${new Date(event.start).toLocaleString('pt-BR')}</p>
                        <p><b>Término:</b> ${new Date(event.end).toLocaleString('pt-BR')}</p>
                    </div>
                `,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: '✏️ Editar',
                denyButtonText: '✅ Concluir',
                cancelButtonText: '❌ Excluir',
                confirmButtonColor: '#3b82f6',
                denyButtonColor: '#16a34a',
                cancelButtonColor: '#ef4444',
                footer: props.payment === 'pending' 
                    ? `<button id="markPaid" class="swal2-confirm swal2-styled" style="background-color:#10b981">💵 Marcar como Pago</button>` 
                    : ''
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/appointments/${event.id}/edit`;
                } else if (result.isDenied) {
                    fetch(`/appointments/${event.id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status: 'done' })
                    }).then(r => {
                        if (r.ok) {
                            Swal.fire('Concluído!', 'Agendamento marcado como concluído.', 'success');
                            calendar.refetchEvents();
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: 'Tem certeza?',
                        text: 'Este agendamento será excluído permanentemente!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Sim, excluir',
                        cancelButtonText: 'Cancelar'
                    }).then(confirm => {
                        if (confirm.isConfirmed) {
                            fetch(`/appointments/${event.id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(r => {
                                if (r.ok) {
                                    Swal.fire('Excluído!', 'O agendamento foi removido.', 'success');
                                    calendar.refetchEvents();
                                }
                            });
                        }
                    });
                }
            });

            // 🔹 Botão "Marcar como Pago"
            document.addEventListener('click', (e) => {
                if (e.target && e.target.id === 'markPaid') {
                    fetch(`/appointments/${event.id}/mark-paid`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    }).then(r => {
                        if (r.ok) {
                            Swal.fire('Pago!', 'O agendamento foi marcado como pago.', 'success');
                            calendar.refetchEvents();
                        }
                    });
                }
            });
        }
    });

    calendar.render();
});
</script>
@endsection
