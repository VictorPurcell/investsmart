<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Metas de Economia
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 text-right">
            <a href="{{ route('goals.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Nova Meta
            </a>
        </div>

        <div class="bg-white shadow rounded overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Mês</th>
                        <th class="p-3">Ano</th>
                        <th class="p-3">Valor</th>
                        <th class="p-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($goals as $goal)
                        <tr class="border-t">
                            <td class="p-3">{{ str_pad($goal->month, 2, '0', STR_PAD_LEFT) }}</td>
                            <td class="p-3">{{ $goal->year }}</td>
                            <td class="p-3 text-green-700">R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</td>
                            <td class="p-3 text-right">
                                <a href="{{ route('goals.edit', $goal) }}" class="text-blue-600 hover:underline mr-2">Editar</a>
                                <form action="{{ route('goals.destroy', $goal) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta meta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-500">Nenhuma meta cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
