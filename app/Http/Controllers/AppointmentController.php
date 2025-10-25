<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\Professional;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Lista todos os agendamentos (tabela).
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
     * Formulário de novo agendamento.
     */
    public function create()
    {
        $clients       = Client::orderBy('name')->get();
        $services      = Service::orderBy('name')->get();
        $professionals = Professional::orderBy('name')->get();

        return view('appointments.create', compact('clients', 'services', 'professionals'));
    }

    /**
     * Salva um novo agendamento.
     */
    public function store(Request $request)
    {
        // ✅ Validação dos campos
        $data = $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'service_id'      => 'required|exists:services,id',
            'professional_id' => 'required|exists:professionals,id',
            'date'            => 'required|date',
            'start_time'      => 'required|date_format:Y-m-d\TH:i',
            'end_time'        => 'required|date_format:Y-m-d\TH:i|after:start_time',
            'price'           => 'required|numeric|min:0',
        ]);

        // ✅ Converte valor para centavos
        $data['price_cents'] = (int) round($data['price'] * 100);
        unset($data['price']);

        // ✅ Define status padrão
        $data['payment_status'] = 'pending';
        $data['status'] = 'scheduled';

        // ✅ Cria o agendamento
        Appointment::create([
            'client_id'       => $data['client_id'],
            'service_id'      => $data['service_id'],
            'professional_id' => $data['professional_id'],
            'date'            => $data['date'],
            'start_time'      => $data['start_time'],
            'end_time'        => $data['end_time'],
            'price_cents'     => $data['price_cents'],
            'payment_status'  => $data['payment_status'],
            'status'          => $data['status'],
        ]);

        // ✅ Redireciona com alerta
        return redirect()
            ->route('reports.appointments')
            ->with('success', 'Agendamento criado com sucesso e adicionado ao calendário!');
    }

    /**
     * Edição de agendamento.
     */
    public function edit(Appointment $appointment)
    {
        $clients       = Client::orderBy('name')->get();
        $services      = Service::orderBy('name')->get();
        $professionals = Professional::orderBy('name')->get();

        return view('appointments.edit', compact('appointment', 'clients', 'services', 'professionals'));
    }

    /**
     * Atualiza o agendamento.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'client_id'       => ['required', 'exists:clients,id'],
            'service_id'      => ['required', 'exists:services,id'],
            'professional_id' => ['required', 'exists:professionals,id'],
            'date'            => ['required', 'date'],
            'start_time'      => ['required', 'date_format:Y-m-d\TH:i'],
            'end_time'        => ['required', 'date_format:Y-m-d\TH:i', 'after:start_time'],
            'status'          => ['required', 'in:scheduled,confirmed,done,canceled'],
            'notes'           => ['nullable', 'string'],
            'price'           => ['nullable', 'numeric', 'min:0'],
            'payment_status'  => ['nullable', 'in:pending,paid'],
        ]);

        if (isset($data['price'])) {
            $data['price_cents'] = (int) round($data['price'] * 100);
            unset($data['price']);
        }

        $appointment->update($data);

        return redirect()
            ->route('appointments.index')
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
}
