<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $categories = Category::where(function ($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'amount' => str_replace(',', '.', $request->input('amount'))
        ]);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'is_fixed' => 'boolean',
        ]);

        $data['user_id'] = Auth::id();

        Transaction::create($data);

        return redirect()->route('transactions.index')->with('success', 'Transação cadastrada com sucesso!');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $categories = Category::where(function ($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->get();

        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $request->merge([
            'amount' => str_replace(',', '.', $request->input('amount'))
        ]);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'is_fixed' => 'boolean',
        ]);

        $transaction->update($data);

        return redirect()->route('transactions.index')->with('success', 'Transação atualizada!');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transação removida.');
    }
}
