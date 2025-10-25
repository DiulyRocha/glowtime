<?php

namespace App\Http\Controllers;

use App\Models\Professional;
use Illuminate\Http\Request;

class ProfessionalController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q');
        $professionals = Professional::when($q, function($query) use ($q){
                $query->where('name','like',"%$q%")
                      ->orWhere('specialties','like',"%$q%")
                      ->orWhere('email','like',"%$q%")
                      ->orWhere('phone','like',"%$q%");
            })
            ->orderBy('name')
            ->paginate(15);
        return view('professionals.index', compact('professionals','q'));
    }

    public function create()
    {
        return view('professionals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'specialties' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'active' => ['nullable','boolean'],
        ]);

        Professional::create([
            'name' => $data['name'],
            'specialties' => $data['specialties'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('professionals.index')->with('success','Profissional cadastrado.');
    }

    public function edit(Professional $professional)
    {
        return view('professionals.edit', compact('professional'));
    }

    public function update(Request $request, Professional $professional)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'specialties' => ['nullable','string','max:255'],
            'phone' => ['nullable','string','max:50'],
            'email' => ['nullable','email','max:255'],
            'active' => ['nullable','boolean'],
        ]);

        $professional->update([
            'name' => $data['name'],
            'specialties' => $data['specialties'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'active' => $request->boolean('active'),
        ]);

        return redirect()->route('professionals.index')->with('success','Profissional atualizado.');
    }

    public function destroy(Professional $professional)
    {
        $professional->delete();
        return back()->with('success','Profissional excluído.');
    }
}
