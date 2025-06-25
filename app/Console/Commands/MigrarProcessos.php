<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Processo;
use Illuminate\Support\Facades\DB;

class MigrarProcessos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processos:migrar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra os dados existentes de processos para incluir user tracking';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando migração de processos...');

        // Obtém o primeiro usuário admin como fallback
        $defaultUser = DB::table('users')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })
            ->first();

        if (!$defaultUser) {
            $this->error('Nenhum usuário admin encontrado para ser usado como fallback!');
            return 1;
        }

        $total = Processo::count();
        $bar = $this->output->createProgressBar($total);

        $this->info('Atualizando ' . $total . ' processos...');

        Processo::chunk(100, function ($processos) use ($defaultUser, $bar) {
            foreach ($processos as $processo) {
                // Atualiza apenas se não tiver user tracking
                if (!$processo->created_by) {
                    $processo->created_by = $defaultUser->id;
                    $processo->updated_by = $defaultUser->id;
                    $processo->save();
                }
                
                $bar->advance();
            }
        });

        $bar->finish();

        $this->newLine();
        $this->info('Migração de processos concluída com sucesso!');

        return 0;
    }
}
