<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Editar Meta de Economia
        </h2>
    </x-slot>

    <div class="py-8 max-w-lg mx-auto px-4">
        <form method="POST" action="{{ route('goals.update', $goal) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-medium mb-1">Mês</label>
                <input type="number" name="month" class="w-full border rounded px-3 py-2" min="1" max="12" value="{{ old('month', $goal->month) }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Ano</label>
                <input type="number" name="year" class="w-full border rounded px-3 py-2" min="2020" max="2100" value="{{ old('year', $goal->year) }}">
            </div>

            <div>
                <label class="block font-medium mb-1">Valor da Meta</label>
                <input type="number" step="0.01" name="target_amount" class="w-full border rounded px-3 py-2" value="{{ old('target_amount', $goal->target_amount) }}">
            </div>

            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Atualizar Meta
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
