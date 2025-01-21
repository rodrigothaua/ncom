<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Processo;

class ProcessoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Processo::create([
            'nome' => 'Processo 1',
            'categoria' => 'consumo',
            'valor_total' => 1000.00,
            'data_inicio' => '2025-01-01',
            'data_fim' => '2025-06-01',
        ]);

        Processo::create([
            'nome' => 'Processo 2',
            'categoria' => 'servico',
            'valor_total' => 2500.00,
            'data_inicio' => '2025-02-01',
            'data_fim' => '2025-12-01',
        ]);
    }
}
