<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Nova Categoria
        </h2>
    </x-slot>

    <div class="py-8 max-w-lg mx-auto px-4">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block font-medium mb-1">Nome</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name') }}">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_global" value="1" class="h-4 w-4">
                <label class="text-sm">Tornar global (visível para todos os usuários)</label>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Criar Categoria
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
