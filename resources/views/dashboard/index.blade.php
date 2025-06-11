<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
            Dashboard Financeiro
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto px-4 space-y-8">
        {{-- Resumo Financeiro --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white shadow rounded p-4">
                <h3 class="text-sm text-gray-500">Saldo Atual</h3>
                <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    R$ {{ number_format($balance, 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-white shadow rounded p-4">
                <h3 class="text-sm text-gray-500">Receitas</h3>
                <p class="text-xl text-green-600 font-semibold">
                    R$ {{ number_format($income, 2, ',', '.') }}
                </p>
            </div>
            <div class="bg-white shadow rounded p-4">
                <h3 class="text-sm text-gray-500">Despesas</h3>
                <p class="text-xl text-red-600 font-semibold">
                    R$ {{ number_format($expenses, 2, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Progresso da Meta --}}
        @if($goal)
            @php
                $metaBatida = $balance >= $goal->target_amount;
                $percentual = min(100, round(($balance / $goal->target_amount) * 100));
            @endphp
            <div class="bg-white shadow rounded p-6">
                <h3 class="text-lg font-semibold mb-2">Meta de Economia - {{ str_pad($goal->month, 2, '0', STR_PAD_LEFT) }}/{{ $goal->year }}</h3>
                <div class="w-full bg-gray-200 rounded-full h-5">
                    <div class="h-5 rounded-full {{ $metaBatida ? 'bg-green-600' : 'bg-yellow-500' }}" style="width: {{ $percentual }}%"></div>
                </div>
                <p class="text-sm mt-2">
                    Progresso: {{ $percentual }}% (meta: R$ {{ number_format($goal->target_amount, 2, ',', '.') }})
                </p>
            </div>
        @endif

        {{-- Alertas ativos --}}
        @if($alerts->count())
            <div class="bg-red-100 text-red-700 border border-red-300 p-4 rounded">
                <h4 class="font-semibold mb-2">Alertas Ativos</h4>
                <ul class="list-disc pl-6 text-sm">
                    @foreach($alerts as $alert)
                        <li>{{ $alert->message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Transações recentes --}}
        <div class="bg-white shadow rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Transações Recentes ({{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }})</h3>

            @if($expensesByCategory->isNotEmpty())
                <div class="bg-white shadow rounded p-6">
                    <h3 class="text-lg font-semibold mb-4">Gastos por Categoria</h3>
                    <canvas id="gastosCategoriaChart" height="200"></canvas>
                </div>
            @endif

            @if($historicoMensal->isNotEmpty())
                <div class="bg-white shadow rounded p-6">
                    <h3 class="text-lg font-semibold mb-4">Evolução Mensal (últimos 6 meses)</h3>
                    <canvas id="evolucaoMensalChart" height="200"></canvas>
                </div>
            @endif

            @if($transactions->isEmpty())

                <p class="text-gray-500 text-sm">Nenhuma transação registrada para este mês.</p>
            @else
                <table class="min-w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">Data</th>
                            <th class="p-3">Descrição</th>
                            <th class="p-3">Categoria</th>
                            <th class="p-3">Tipo</th>
                            <th class="p-3">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions->sortByDesc('date')->take(5) as $transaction)
                            <tr class="border-t">
                                <td class="p-3">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                                <td class="p-3">{{ $transaction->description ?? '-' }}</td>
                                <td class="p-3">{{ $transaction->category->name }}</td>
                                <td class="p-3 capitalize text-gray-600">{{ $transaction->type == 'income' ? 'Receita' : 'Despesa' }}</td>
                                <td class="p-3 {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>

@if($expensesByCategory->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('gastosCategoriaChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($expensesByCategory->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($expensesByCategory->values()) !!},
                        backgroundColor: [
                            '#f87171', '#fb923c', '#facc15',
                            '#4ade80', '#60a5fa', '#a78bfa', '#f472b6'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
@endif

@if($historicoMensal->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctxBar = document.getElementById('evolucaoMensalChart').getContext('2d');

            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($historicoMensal->keys()) !!},
                    datasets: [
                        {
                            label: 'Receitas',
                            backgroundColor: '#4ade80',
                            data: {!! json_encode($historicoMensal->pluck('income')->values()) !!}
                        },
                        {
                            label: 'Despesas',
                            backgroundColor: '#f87171',
                            data: {!! json_encode($historicoMensal->pluck('expense')->values()) !!}
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        });
    </script>
@endif

