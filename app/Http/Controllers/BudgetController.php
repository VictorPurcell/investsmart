<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $categories = Category::where(function ($q) {
            $q->whereNull('user_id')->orWhere('user_id', Auth::id());
        })->orderBy('name')->get();

        $budgets = Budget::where('user_id', Auth::id())
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->keyBy('category_id');

        return view('budgets.index', compact('categories', 'budgets', 'month', 'year'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:0'
        ]);

        $data['user_id'] = Auth::id();

        Budget::updateOrCreate(
            [
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'],
                'month' => $data['month'],
                'year' => $data['year'],
            ],
            ['limit_amount' => $data['limit_amount']]
        );

        return redirect()->route('budgets.index', [
            'month' => $data['month'],
            'year' => $data['year']
        ])->with('success', 'Orçamento salvo com sucesso.');
    }
}
