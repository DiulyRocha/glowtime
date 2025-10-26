<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\Professional;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\AppointmentCreatedNotification;

class AppointmentController extends Controller
{
    /**
     * Lista todos os agendamentos (tabela principal).
     */
    public function index()
    {
        $appointments = Appointment::with(['client', 'service', 'professional'])
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Exibe o formulário de novo agendamento.
     */
    public function create()
    {
        $clients       = Client::orderBy('name')->get();
        $services      = Service::orderBy('name')->get();
        $professionals = Professional::orderBy('name')->get();

        return view('appointments.create', compact('clients', 'services', 'professionals'));
    }

    /**
     * Salva um novo agendamento (formulário ou JSON via calendário).
     */
    public function store(Request $request)
    {
        // 🔹 Detecta se veio via JSON (calendário)
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        // 🔹 Validação
        $validated = validator($data, [
            'client_id'       => 'required|exists:clients,id',
            'service_id'      => 'required|exists:services,id',
            'professional_id' => 'required|exists:professionals,id',
            'date'            => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'required|after:start_time',
            'price'           => 'required|numeric|min:0',
        ])->validate();

        // 🔹 Garante formato correto dos horários
        $validated['start_time'] = Carbon::parse($validated['start_time'])->format('H:i:s');
        $validated['end_time']   = Carbon::parse($validated['end_time'])->format('H:i:s');

        // 🔹 Converte valor para centavos
        $validated['price_cents'] = (int) round($validated['price'] * 100);
        unset($validated['price']);

        // 🔹 Define status padrão
        $validated['payment_status'] = 'pending';
        $validated['status'] = 'scheduled';

        // 🔹 Cria o agendamento
        $appointment = Appointment::create($validated);

        // 🔹 Envia notificação por e-mail (somente se o cliente tiver e-mail)
        if ($appointment->client && $appointment->client->email) {
            $appointment->client->notify(new AppointmentCreatedNotification($appointment));
        }

        // 🔹 Retorno JSON (para o calendário)
        if ($request->isJson()) {
            return response()->json([
                'message' => 'Agendamento criado com sucesso!',
                'appointment' => $appointment
            ], 201);
        }

        // 🔹 Retorno padrão (formulário Laravel)
        return redirect()
            ->route('reports.appointments')
            ->with('success', 'Agendamento criado com sucesso!');
    }

    /**
     * Exibe o formulário de edição.
     */
    public function edit(Appointment $appointment)
    {
        $clients       = Client::orderBy('name')->get();
        $services      = Service::orderBy('name')->get();
        $professionals = Professional::orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'clients', 'services', 'professionals'));
    }

    /**
     * Retorna detalhes do agendamento (usado no clique do calendário).
     */
    public function show(Appointment $appointment)
    {
        return response()->json([
            'id'           => $appointment->id,
            'cliente'      => $appointment->client->name ?? 'Não informado',
            'serviço'      => $appointment->service->name ?? 'Não informado',
            'profissional' => $appointment->professional->name ?? 'Não informado',
            'data'         => Carbon::parse($appointment->date)->format('d/m/Y'),
            'inicio'       => Carbon::parse($appointment->start_time)->format('H:i'),
            'fim'          => Carbon::parse($appointment->end_time)->format('H:i'),
            'valor'        => number_format($appointment->price_cents / 100, 2, ',', '.'),
            'status'       => ucfirst($appointment->status),
            'pagamento'    => $appointment->payment_status === 'paid' ? 'Pago' : 'Pendente',
        ]);
    }

    /**
     * Atualiza o agendamento.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'service_id'      => 'required|exists:services,id',
            'professional_id' => 'required|exists:professionals,id',
            'date'            => 'required|date',
            'start_time'      => 'required',
            'end_time'        => 'required|after:start_time',
            'status'          => 'required|in:scheduled,done,canceled',
            'payment_status'  => 'required|in:pending,paid',
            'price'           => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        if (isset($data['price'])) {
            $data['price_cents'] = (int) round($data['price'] * 100);
            unset($data['price']);
        }

        $appointment->update($data);

        return redirect()
            ->route('reports.appointments')
            ->with('success', 'Agendamento atualizado com sucesso!');
    }

    /**
     * Exclui o agendamento.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return back()->with('success', 'Agendamento excluído com sucesso!');
    }

    /**
     * Retorna os agendamentos para o calendário (FullCalendar).
     */
    public function events()
    {
        $appointments = Appointment::with(['client', 'service', 'professional'])->get();

        $events = $appointments->map(function ($a) {
            return [
                'id'    => $a->id,
                'title' => "{$a->client->name} - {$a->service->name}",
                'start' => "{$a->date}T{$a->start_time}",
                'end'   => "{$a->date}T{$a->end_time}",
                'color' => match ($a->payment_status) {
                    'paid'    => '#22c55e',  // verde
                    'pending' => '#facc15',  // amarelo
                    default   => '#9ca3af',  // cinza
                },
                'extendedProps' => [
                    'profissional' => $a->professional->name ?? 'Não informado',
                    'valor'        => $a->price_cents / 100,
                    'status'       => $a->status,
                    'payment'      => $a->payment_status,
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Relatório de agendamentos.
     */
    public function report(Request $request)
    {
        $query = Appointment::with(['client', 'service', 'professional']);

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('date', 'desc')->paginate(15);

        $summary = [
            'total'     => Appointment::count(),
            'scheduled' => Appointment::where('status', 'scheduled')->count(),
            'done'      => Appointment::where('status', 'done')->count(),
            'canceled'  => Appointment::where('status', 'canceled')->count(),
            'paid'      => Appointment::where('payment_status', 'paid')->count(),
            'revenue'   => number_format(
                Appointment::where('payment_status', 'paid')->sum('price_cents') / 100,
                2,
                ',',
                '.'
            ),
        ];

        return view('reports.appointments_report', [
            'appointments' => $appointments,
            'summary'      => $summary,
        ]);
    }

    /**
     * Marca o agendamento como pago.
     */
    public function markAsPaid($id)
    {
        $appointment = Appointment::findOrFail($id);

        $appointment->update([
            'payment_status' => 'paid',
            'status'         => $appointment->status === 'canceled' ? 'canceled' : 'done',
        ]);

        return redirect()
            ->route('reports.appointments')
            ->with('success', '💸 Agendamento marcado como pago e incluído nos relatórios financeiros!');
    }

    /**
     * Exibe o calendário interativo.
     */
    public function calendar()
    {
        $clients = Client::orderBy('name')->get(['id', 'name']);
        $services = Service::orderBy('name')->get(['id', 'name']);
        $professionals = Professional::orderBy('name')->get(['id', 'name']);

        return view('dashboard', compact('clients', 'services', 'professionals'));
    }
}
