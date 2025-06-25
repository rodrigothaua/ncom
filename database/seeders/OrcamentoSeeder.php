<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FonteOrcamento;
use App\Models\CategoriaDespesa;
use App\Models\User;

class OrcamentoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Iniciando seed do sistema de orçamentos...');

        // Buscar ou criar usuário admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('123456'),
                'role' => 'admin'
            ]
        );

        // Criar fontes de orçamento padrão
        $this->criarFontes($admin->id);

        // Criar categorias de despesa padrão
        $this->criarCategorias($admin->id);

        $this->command->info('Seed do sistema de orçamentos concluído!');
    }

    private function criarFontes($userId)
    {
        $fontes = [
            [
                'codigo' => 'FED001',
                'nome' => 'Orçamento Federal',
                'tipo' => 'Federal',
                'descricao' => 'Recursos provenientes do orçamento federal'
            ],
            [
                'codigo' => 'EST001',
                'nome' => 'Orçamento Estadual',
                'tipo' => 'Estadual',
                'descricao' => 'Recursos provenientes do orçamento estadual'
            ],
            [
                'codigo' => 'MUN001',
                'nome' => 'Orçamento Municipal',
                'tipo' => 'Municipal',
                'descricao' => 'Recursos provenientes do orçamento municipal'
            ],
            [
                'codigo' => 'EMP001',
                'nome' => 'Emenda Parlamentar',
                'tipo' => 'Emenda Parlamentar',
                'descricao' => 'Recursos provenientes de emendas parlamentares'
            ]
        ];

        foreach ($fontes as $fonte) {
            FonteOrcamento::firstOrCreate(
                ['codigo' => $fonte['codigo']],
                [
                    'nome' => $fonte['nome'],
                    'tipo' => $fonte['tipo'],
                    'descricao' => $fonte['descricao'],
                    'ativo' => true,
                    'created_by' => $userId
                ]
            );
        }

        $this->command->info('Fontes de orçamento criadas com sucesso!');
    }

    private function criarCategorias($userId)
    {
        $categorias = [
            [
                'codigo' => 'MAT-CONS',
                'nome' => 'Material de Consumo',
                'descricao' => 'Materiais de consumo em geral'
            ],
            [
                'codigo' => 'MAT-PERM',
                'nome' => 'Material Permanente',
                'descricao' => 'Equipamentos e materiais permanentes'
            ],
            [
                'codigo' => 'SERV-PF',
                'nome' => 'Serviços de Pessoa Física',
                'descricao' => 'Serviços prestados por pessoas físicas'
            ],
            [
                'codigo' => 'SERV-PJ',
                'nome' => 'Serviços de Pessoa Jurídica',
                'descricao' => 'Serviços prestados por pessoas jurídicas'
            ],
            [
                'codigo' => 'OBRAS',
                'nome' => 'Obras e Instalações',
                'descricao' => 'Obras, reformas e instalações'
            ]
        ];

        foreach ($categorias as $categoria) {
            CategoriaDespesa::firstOrCreate(
                ['codigo' => $categoria['codigo']],
                [
                    'nome' => $categoria['nome'],
                    'descricao' => $categoria['descricao'],
                    'ativo' => true,
                    'created_by' => $userId
                ]
            );
        }

        $this->command->info('Categorias de despesa criadas com sucesso!');
    }
}
