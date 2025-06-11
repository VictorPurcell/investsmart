<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Editar Categoria
        </h2>
    </x-slot>

    <div class="py-8 max-w-lg mx-auto px-4">
        <form method="POST" action="{{ route('categories.update', $category) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1">Nome</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', $category->name) }}">
                @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="text-gray-600 text-sm">
                Tipo: {{ $category->user_id ? 'Pessoal' : 'Global' }}
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Atualizar Categoria
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
