<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Nova Transação
        </h2>
    </x-slot>

    <div class="py-8 max-w-xl mx-auto px-4">
        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-6">
            @csrf

            {{-- Categoria --}}
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                <select name="category_id" id="category_id" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Selecione</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo --}}
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                <select name="type" id="type" class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">Selecione</option>
                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Receita</option>
                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Despesa</option>
                </select>
                @error('type') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Valor --}}
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor</label>
                <input type="number" step="0.01" min="0" name="amount" id="amount"
                    class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    value="{{ old('amount') }}">
                @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Descrição --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <input type="text" name="description" id="description"
                    class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    value="{{ old('description') }}">
            </div>

            {{-- Data --}}
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data</label>
                <input type="date" name="date" id="date"
                    class="w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    value="{{ old('date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                @error('date') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Custo Fixo --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_fixed" id="is_fixed" value="1"
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    {{ old('is_fixed') ? 'checked' : '' }}>
                <label for="is_fixed" class="text-sm text-gray-700 dark:text-gray-300">É custo fixo?</label>
            </div>

            {{-- Botão --}}
            <div class="text-right">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-sm transition duration-200">
                    Salvar Transação
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
