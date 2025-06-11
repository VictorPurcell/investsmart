<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function index()
    {
        $goals = Goal::where('user_id', Auth::id())
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->get();

        return view('goals.index', compact('goals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'target_amount' => 'required|numeric|min:0',
        ]);

        $data['user_id'] = Auth::id();

        Goal::create($data);

        return redirect()->route('goals.index')->with('success', 'Meta criada com sucesso!');
    }

    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);

        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $data = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'target_amount' => 'required|numeric|min:0',
        ]);

        $goal->update($data);

        return redirect()->route('goals.index')->with('success', 'Meta atualizada!');
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);
        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Meta removida.');
    }
}

