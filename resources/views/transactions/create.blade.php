@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto py-8 px-4">
    <h1 class="text-2xl font-bold mb-6">Nova Transação</h1>

    <form method="POST" action="{{ route('transactions.store') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block font-medium mb-1">Categoria</label>
            <select name="category_id" class="w-full border rounded px-3 py-2">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Tipo</label>
            <select name="type" class="w-full border rounded px-3 py-2">
                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Receita</option>
                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Despesa</option>
            </select>
            @error('type') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Valor</label>
            <input type="number" step="0.01" name="amount" class="w-full border rounded px-3 py-2" value="{{ old('amount') }}">
            @error('amount') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block font-medium mb-1">Descrição</label>
            <input type="text" name="description" class="w-full border rounded px-3 py-2" value="{{ old('description') }}">
        </div>

        <div>
            <label class="block font-medium mb-1">Data</label>
            <input type="date" name="date" class="w-full border rounded px-3 py-2" value="{{ old('date') }}">
            @error('date') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_fixed" value="1" class="h-4 w-4">
            <label class="text-sm">É custo fixo?</label>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Salvar Transação
            </button>
        </div>
    </form>
</div>
@endsection
