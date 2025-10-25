<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q');
        $services = Service::when($q, function($query) use ($q){
                $query->where('name','like',"%$q%");
            })
            ->orderBy('name')
            ->paginate(15);
        return view('services.index', compact('services','q'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'duration_minutes' => ['required','integer','min:5','max:600'],
            'price' => ['required','string'], // em R$ (ex.: 120,50)
            'active' => ['nullable','boolean'],
        ]);

        // aceita "1.200,50" ou "1200.50"
        $priceStr = preg_replace('/[^\d,\.]/', '', $data['price']);
        $priceStr = str_replace(['.',','], ['', '.'], $priceStr);
        $priceCents = (int) round(((float)$priceStr) * 100);

        Service::create([
            'name' => $data['name'],
            'duration_minutes' => $data['duration_minutes'],
            'price_cents' => $priceCents,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('services.index')->with('success','Serviço cadastrado.');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'duration_minutes' => ['required','integer','min:5','max:600'],
            'price' => ['required','string'],
            'active' => ['nullable','boolean'],
        ]);

        $priceStr = preg_replace('/[^\d,\.]/', '', $data['price']);
        $priceStr = str_replace(['.',','], ['', '.'], $priceStr);
        $priceCents = (int) round(((float)$priceStr) * 100);

        $service->update([
            'name' => $data['name'],
            'duration_minutes' => $data['duration_minutes'],
            'price_cents' => $priceCents,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('services.index')->with('success','Serviço atualizado.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success','Serviço excluído.');
    }
}
