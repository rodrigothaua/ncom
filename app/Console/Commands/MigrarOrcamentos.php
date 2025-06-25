<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\FonteOrcamento;
use App\Models\CategoriaDespesa;
use App\Models\Orcamento;
use App\Models\AlocacaoOrcamento;
use App\Models\User;

class MigrarOrcamentos extends Command
{
    protected $signature = 'orcamentos:migrar';
    protected $description = 'Migra os dados do sistema antigo de orçamentos para a nova estrutura';

    public function handle()
    {
        $this->info('Iniciando migração dos orçamentos...');

        try {
            DB::beginTransaction();

            // 1. Migrar fontes de orçamento
            $this->info('Migrando fontes de orçamento...');
            $this->migrarFontes();

            // 2. Migrar categorias de despesa
            $this->info('Migrando categorias de despesa...');
            $this->migrarCategorias();

            // 3. Migrar orçamentos
            $this->info('Migrando orçamentos...');
            $this->migrarOrcamentos();

            // 4. Migrar alocações
            $this->info('Migrando alocações de orçamento...');
            $this->migrarAlocacoes();

            DB::commit();
            $this->info('Migração concluída com sucesso!');
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Erro durante a migração: ' . $e->getMessage());
            return 1;
        }
    }

    private function migrarFontes()
    {
        // Criar fonte padrão se não existir
        FonteOrcamento::firstOrCreate(
            ['codigo' => '001'],
            [
                'nome' => 'Orçamento Federal',
                'tipo' => 'Federal',
                'descricao' => 'Recursos do orçamento federal',
                'ativo' => true,
                'created_by' => User::first()->id
            ]
        );
    }

    private function migrarCategorias()
    {
        $categorias = [
            [
                'codigo' => 'CONS',
                'nome' => 'Material de Consumo',
                'descricao' => 'Materiais de consumo diversos'
            ],
            [
                'codigo' => 'PERM',
                'nome' => 'Material Permanente',
                'descricao' => 'Equipamentos e materiais permanentes'
            ],
            [
                'codigo' => 'SERV',
                'nome' => 'Serviços',
                'descricao' => 'Prestação de serviços'
            ]
        ];

        foreach ($categorias as $cat) {
            CategoriaDespesa::firstOrCreate(
                ['codigo' => $cat['codigo']],
                [
                    'nome' => $cat['nome'],
                    'descricao' => $cat['descricao'],
                    'ativo' => true,
                    'created_by' => User::first()->id
                ]
            );
        }
    }

    private function migrarOrcamentos()
    {
        // Buscar orçamentos antigos
        $orcamentosAntigos = DB::table('old_orcamentos')->get();
        $fonteId = FonteOrcamento::first()->id;
        $userId = User::first()->id;

        foreach ($orcamentosAntigos as $antigo) {
            $orcamento = Orcamento::create([
                'fonte_orcamento_id' => $fonteId,
                'numero_orcamento' => $antigo->numero ?? date('Y') . sprintf('%06d', $antigo->id),
                'valor_total' => $antigo->valor_total ?? 0,
                'valor_utilizado' => $antigo->valor_utilizado ?? 0,
                'ano_exercicio' => $antigo->ano_exercicio ?? date('Y'),
                'descricao' => $antigo->descricao,
                'data_inicio' => $antigo->data_inicio,
                'data_fim' => $antigo->data_fim,
                'status' => $this->mapearStatus($antigo->status),
                'created_by' => $userId
            ]);

            $this->info("Orçamento {$orcamento->numero_orcamento} migrado.");
        }
    }

    private function migrarAlocacoes()
    {
        // Buscar detalhes de despesa antigos
        $detalhesAntigos = DB::table('detalhes_despesa')->get();
        $userId = User::first()->id;

        foreach ($detalhesAntigos as $detalhe) {
            // Identificar categoria baseado no tipo de despesa
            $categoria = null;
            if ($detalhe->valor_consumo > 0) {
                $categoria = CategoriaDespesa::where('codigo', 'CONS')->first();
                $valor = $detalhe->valor_consumo;
            } elseif ($detalhe->valor_permanente > 0) {
                $categoria = CategoriaDespesa::where('codigo', 'PERM')->first();
                $valor = $detalhe->valor_permanente;
            } elseif ($detalhe->valor_servico > 0) {
                $categoria = CategoriaDespesa::where('codigo', 'SERV')->first();
                $valor = $detalhe->valor_servico;
            }

            if ($categoria && $valor > 0) {
                AlocacaoOrcamento::create([
                    'orcamento_id' => $detalhe->orcamento_id,
                    'categoria_despesa_id' => $categoria->id,
                    'processo_id' => $detalhe->processo_id,
                    'valor_alocado' => $valor,
                    'valor_utilizado' => $valor, // Assumindo que valores antigos já foram utilizados
                    'status' => 'Pago',
                    'created_by' => $userId
                ]);

                $this->info("Alocação migrada para processo {$detalhe->processo_id}");
            }
        }
    }

    private function mapearStatus($statusAntigo)
    {
        $mapa = [
            'pendente' => 'Em Análise',
            'aprovado' => 'Aprovado',
            'reprovado' => 'Reprovado',
            'em_execucao' => 'Em Execução',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado'
        ];

        return $mapa[strtolower($statusAntigo)] ?? 'Em Análise';
    }
}
