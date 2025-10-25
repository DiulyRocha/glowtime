<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;


class ClientController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q');
        $clients = Client::when($q, fn($qq) => $qq->where('name', 'like', "%$q%")
            ->orWhere('email', 'like', "%$q%")
            ->orWhere('phone', 'like', "%$q%"))
            ->orderBy('name')
            ->paginate(15);
        return view('clients.index', compact('clients', 'q'));
    }


    public function create()
    {
        return view('clients.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'unique:clients,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:clients,email'],
            'birth_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string']
        ]);
        
        Client::create($data);
        return redirect()->route('clients.index')->with('success', 'Cliente cadastrado.');
    }


    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }


    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30', 'unique:clients,phone,' . $client->id],
            'email' => ['required', 'email', 'max:255', 'unique:clients,email,' . $client->id],
            'birth_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string']
        ]);
        $client->update($data);
        return redirect()->route('clients.index')->with('success', 'Cliente atualizado.');
    }


    public function destroy(Client $client)
    {
        $client->delete();
        return back()->with('success', 'Cliente excluído.');
    }
}
