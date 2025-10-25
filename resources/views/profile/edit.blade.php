<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow">
                @if(session('success'))
                    <div class="mb-4 text-green-600">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block">Nome</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="border rounded w-full" required>
                        @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border rounded w-full" required>
                        @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block">Nova Senha (opcional)</label>
                        <input type="password" name="password" class="border rounded w-full">
                        @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" class="border rounded w-full">
                    </div>

                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded">
                        Salvar
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
