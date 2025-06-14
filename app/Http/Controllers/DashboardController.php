<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Goal;
use App\Models\Alert;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $now = Carbon::now();
        $month = $request->input('month', $now->month);
        $year = $request->input('year', $now->year);

        $transactions = Transaction::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $expensesByCategory = $transactions
            ->where('type', 'expense')
            ->groupBy('category.name')
            ->map(function ($group) {
                return $group->sum('amount');
            });
            
        $historicoMensal = Transaction::where('user_id', $user->id)
            ->whereBetween('date', [
                now()->copy()->subMonths(5)->startOfMonth(),
                now()->endOfMonth()
            ])
            ->get()
            ->groupBy(function ($t) {
                return \Carbon\Carbon::parse($t->date)->format('m/Y');
            })
            ->map(function (Collection $transacoesMes) {
                return [
                    'income' => $transacoesMes->where('type', 'income')->sum('amount'),
                    'expense' => $transacoesMes->where('type', 'expense')->sum('amount'),
                ];
            });

        $goal = Goal::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        $alerts = Alert::where('user_id', $user->id)
            ->where('read', false)
            ->get();

        $income = $transactions->where('type', 'income')->sum('amount');
        $expenses = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expenses;

        return view('dashboard.index', compact(
            'month', 'year', 'transactions', 'goal', 'alerts',
            'income', 'expenses', 'balance', 'expensesByCategory',
            'historicoMensal'
        ));
    }
}

