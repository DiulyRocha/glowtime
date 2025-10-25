// ================================
// 🌸 GlowTime – Calendário de Agendamentos
// ================================

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import ptLocale from '@fullcalendar/core/locales/pt-br';
import Swal from 'sweetalert2';

// ================================
// ⚙️ Inicialização do calendário
// ================================
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');

    if (calendarEl) {
        const eventsUrl = calendarEl.dataset.events;

        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            locale: ptLocale,
            height: 'auto',
            selectable: true,

            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },

            // 🔗 Busca eventos do backend
            events: eventsUrl,

            // 💅 Cores personalizadas por status
            eventDidMount: function (info) {
                const status = info.event.extendedProps.status;

                if (status === 'canceled') {
                    info.el.style.backgroundColor = '#9ca3af'; // cinza
                    info.el.style.borderColor = '#9ca3af';
                } else if (status === 'done') {
                    info.el.style.backgroundColor = '#22c55e'; // verde
                    info.el.style.borderColor = '#16a34a';
                } else if (status === 'scheduled') {
                    info.el.style.backgroundColor = '#facc15'; // amarelo
                    info.el.style.borderColor = '#eab308';
                } else {
                    info.el.style.backgroundColor = '#ec4899'; // rosa padrão
                    info.el.style.borderColor = '#db2777';
                }
            },

            // 🔍 Clique em evento → mostra detalhes
            eventClick: function (info) {
                const event = info.event;
                Swal.fire({
                    title: `<strong>${event.title}</strong>`,
                    html: `
                        <p><b>Profissional:</b> ${event.extendedProps.profissional}</p>
                        <p><b>Valor:</b> R$ ${event.extendedProps.valor.toFixed(2)}</p>
                        <p><b>Status:</b> ${event.extendedProps.status}</p>
                        <p><b>Pagamento:</b> ${event.extendedProps.payment}</p>
                        <p><b>Início:</b> ${new Date(event.start).toLocaleString('pt-BR')}</p>
                        <p><b>Término:</b> ${new Date(event.end).toLocaleString('pt-BR')}</p>
                    `,
                    icon: 'info',
                    confirmButtonColor: '#ec4899',
                    confirmButtonText: 'Fechar'
                });
            }
        });

        calendar.render();
    }
});
