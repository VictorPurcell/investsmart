<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Categorias
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Nova Categoria
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nome</th>
                        <th class="p-3">Tipo</th>
                        <th class="p-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr class="border-t">
                            <td class="p-3">{{ $category->name }}</td>
                            <td class="p-3">
                                @if ($category->user_id)
                                    <span class="text-sm text-gray-600">Pessoal</span>
                                @else
                                    <span class="text-sm text-gray-500 italic">Global</span>
                                @endif
                            </td>
                            <td class="p-3 text-right">
                                @can('update', $category)
                                    <a href="{{ route('categories.edit', $category) }}" class="text-blue-600 hover:underline mr-2">Editar</a>
                                @endcan
                                @can('delete', $category)
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Deseja excluir esta categoria?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-gray-500">Nenhuma categoria encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
