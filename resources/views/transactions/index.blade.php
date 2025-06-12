<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Minhas Transações
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4 flex justify-end space-x-2">
            <a href="{{ route('transactions.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Nova Transação
            </a>

            <a href="{{ route('transactions.export') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                📤 Exportar CSV
            </a>
        </div>

        <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Data</th>
                        <th class="p-3">Descrição</th>
                        <th class="p-3">Categoria</th>
                        <th class="p-3">Tipo</th>
                        <th class="p-3">Valor</th>
                        <th class="p-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="border-t">
                            <td class="p-3">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                            <td class="p-3">{{ $transaction->description ?? '-' }}</td>
                            <td class="p-3">{{ $transaction->category->name }}</td>
                            <td class="p-3 capitalize">{{ $transaction->type == 'income' ? 'Receita' : 'Despesa' }}</td>
                            <td class="p-3 text-green-600 font-medium">
                                R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                            </td>
                            <td class="p-3 text-right">
                                <a href="{{ route('transactions.edit', $transaction) }}" class="text-blue-600 hover:underline mr-2">Editar</a>
                                <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" class="inline-block" onsubmit="return confirm('Deseja excluir esta transação?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">Nenhuma transação encontrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</x-app-layout>
