<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fonte_orcamento_id')->constrained('fontes_orcamento');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->string('numero_orcamento')->unique();
            $table->decimal('valor_total', 15, 2);
            $table->decimal('valor_utilizado', 15, 2)->default(0);
            $table->string('ano_exercicio');
            $table->text('descricao')->nullable();
            $table->string('parlamentar')->nullable(); // Para casos de emenda parlamentar
            $table->string('partido')->nullable(); // Para casos de emenda parlamentar
            $table->string('numero_convenio')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->enum('status', [
                'Em Análise',
                'Aprovado',
                'Reprovado',
                'Em Execução',
                'Concluído',
                'Cancelado'
            ])->default('Em Análise');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        // Tabela pivot para vincular orçamentos aos detalhes de despesa
        Schema::create('detalhes_despesa_orcamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalhes_despesa_id')->constrained('detalhes_despesa')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->decimal('valor_alocado', 15, 2);
            $table->decimal('valor_utilizado', 15, 2)->default(0);
            $table->string('numero_nota_empenho')->nullable();
            $table->date('data_empenho')->nullable();
            $table->enum('status', [
                'Planejado',
                'Empenhado',
                'Liquidado',
                'Pago',
                'Cancelado'
            ])->default('Planejado');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            // Garante que um detalhe de despesa não tenha duplicidade de alocação para o mesmo orçamento
            $table->unique(['detalhes_despesa_id', 'orcamento_id'], 'detalhe_orcamento_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalhes_despesa_orcamento');
        Schema::dropIfExists('orcamentos');
    }
};
