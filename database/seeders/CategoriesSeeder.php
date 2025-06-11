<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            'Alimentação',
            'Transporte',
            'Lazer',
            'Educação',
            'Saúde',
            'Moradia',
            'Vestuário',
            'Assinaturas e Serviços',
            'Doações',
            'Impostos',
            'Salário',
            'Investimentos',
            'Freelas',
            'Reembolsos',
            'Outros',
        ];

        foreach ($categorias as $nome) {
            DB::table('categories')->updateOrInsert(
                ['name' => $nome, 'user_id' => null],
                [
                    'is_global' => true,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}

