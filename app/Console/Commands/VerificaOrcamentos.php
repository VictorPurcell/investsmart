<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Budget;
use App\Models\Transaction;
use App\Models\Alert;
use Illuminate\Support\Carbon;

class VerificaOrcamentos extends Command
{
    protected $signature = 'alertas:verificar-orcamentos';
    protected $description = 'Verifica se categorias ultrapassaram orçamento mensal e gera alertas';

    public function handle()
    {
        $this->info("Verificando orçamentos...");

        $now = Carbon::now();
        $mes = $now->month;
        $ano = $now->year;

        $budgets = Budget::with('category')->where([
            ['month', $mes],
            ['year', $ano],
        ])->get();

        foreach ($budgets as $budget) {
            $gastoTotal = Transaction::where('user_id', $budget->user_id)
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('date', $mes)
                ->whereYear('date', $ano)
                ->sum('amount');

            if ($gastoTotal > $budget->limit_amount) {
                $mensagem = "Você ultrapassou o orçamento da categoria '{$budget->category->name}' em " .
                            "R$ " . number_format($gastoTotal - $budget->limit_amount, 2, ',', '.');

                // Evita duplicação
                $alertaExistente = Alert::where([
                    ['user_id', $budget->user_id],
                    ['type', 'budget_exceeded'],
                    ['message', $mensagem],
                ])->exists();

                if (!$alertaExistente) {
                    Alert::create([
                        'user_id' => $budget->user_id,
                        'type' => 'budget_exceeded',
                        'message' => $mensagem,
                        'read' => false,
                    ]);
                    $this->info("Alerta criado para usuário {$budget->user_id}: $mensagem");
                }
            }
        }

        $this->info("Verificação concluída.");
    }
}

