<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Editar Transação
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1">Categoria</label>
                <select name="category_id" class="w-full border rounded px-3 py-2">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $transaction->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Tipo</label>
                <select name="type" class="w-full border rounded px-3 py-2">
                    <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Receita</option>
                    <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Despesa</option>
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Valor</label>
                <input type="number" step="0.01" name="amount" class="w-full border rounded px-3 py-2" value="{{ $transaction->amount }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Descrição</label>
                <input type="text" name="description" class="w-full border rounded px-3 py-2" value="{{ $transaction->description }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Data</label>
                <input type="date" name="date" class="w-full border rounded px-3 py-2" value="{{ $transaction->date->format('Y-m-d') }}">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_fixed" value="1" class="h-4 w-4" {{ $transaction->is_fixed ? 'checked' : '' }}>
                <label class="text-sm">É custo fixo?</label>
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Atualizar Transação
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
