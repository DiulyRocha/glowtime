@extends('layouts.guest')

@section('content')
    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <label>Email</label>
            <input class="w-full border rounded p-2" type="email" name="email" required>
        </div>

        <!-- Senha -->
        <div class="mb-4">
            <label>Senha</label>
            <input class="w-full border rounded p-2" type="password" name="password" required>
        </div>

        <!-- Remember me -->
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="mr-2">
                Lembrar-me
            </label>
        </div>

        <!-- Botão -->
        <button class="w-full bg-pink-600 text-white p-2 rounded">
            Entrar
        </button>
    </form>
@endsection
