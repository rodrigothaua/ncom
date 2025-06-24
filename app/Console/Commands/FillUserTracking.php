<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Processo;
use App\Models\Orcamento;
use App\Models\User;

class FillUserTracking extends Command
{
    protected $signature = 'fill:user-tracking';
    protected $description = 'Preenche os campos de rastreamento de usuário para registros existentes';

    public function handle()
    {
        $this->info('Iniciando preenchimento de dados de rastreamento de usuário...');

        // Busca o primeiro usuário admin do sistema
        $defaultUser = User::first();

        if (!$defaultUser) {
            $this->error('Nenhum usuário encontrado no sistema!');
            return 1;
        }

        $this->info('Usando usuário padrão: ' . $defaultUser->name);

        // Atualiza processos
        $this->info('Atualizando processos...');
        $processos = Processo::whereNull('created_by')->get();
        foreach ($processos as $processo) {
            $processo->created_by = $defaultUser->id;
            $processo->updated_by = $defaultUser->id;
            $processo->save();
        }
        $this->info($processos->count() . ' processos atualizados.');

        // Atualiza orçamentos
        $this->info('Atualizando orçamentos...');
        $orcamentos = Orcamento::whereNull('created_by')->get();
        foreach ($orcamentos as $orcamento) {
            $orcamento->created_by = $defaultUser->id;
            $orcamento->updated_by = $defaultUser->id;
            $orcamento->save();
        }
        $this->info($orcamentos->count() . ' orçamentos atualizados.');

        $this->info('Processo finalizado com sucesso!');
        return 0;
    }
}
