<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Orçamentos Mensais por Categoria
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto px-4 space-y-6">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filtro por mês e ano --}}
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mês</label>
                <select name="month" class="border rounded px-3 py-2">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ano</label>
                <select name="year" class="border rounded px-3 py-2">
                    @for($y = now()->year; $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Aplicar Filtro
                </button>
            </div>
        </form>

        {{-- Lista de categorias com inputs --}}
        <form method="POST" action="{{ route('budgets.store') }}">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year" value="{{ $year }}">

            <div class="bg-white shadow rounded overflow-x-auto">
                <table class="min-w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Categoria</th>
                            <th class="p-3">Valor Limite (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="border-t">
                                <td class="p-3">{{ $category->name }}</td>
                                <td class="p-3">
                                    <input
                                        type="number"
                                        step="0.01"
                                        name="limit_amount"
                                        value="{{ $budgets[$category->id]->limit_amount ?? '' }}"
                                        class="border rounded px-3 py-1 w-40"
                                        placeholder="Ex: 300.00"
                                    >
                                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Salvar Orçamento
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
