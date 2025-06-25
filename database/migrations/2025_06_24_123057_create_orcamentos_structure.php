<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Fontes de Orçamento
        Schema::create('fontes_orcamento', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('codigo')->unique();
            $table->enum('tipo', ['Federal', 'Estadual', 'Municipal', 'Emenda Parlamentar', 'Outro'])->default('Federal');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Orçamentos
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fonte_orcamento_id')->constrained('fontes_orcamento');
            $table->string('numero_orcamento')->unique();
            $table->decimal('valor_total', 15, 2);
            $table->decimal('valor_utilizado', 15, 2)->default(0);
            $table->string('ano_exercicio');
            $table->text('descricao')->nullable();
            $table->string('parlamentar')->nullable();
            $table->string('partido')->nullable();
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
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Categorias de Despesa
        Schema::create('categorias_despesa', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('codigo')->unique();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Alocações de Orçamento
        Schema::create('alocacoes_orcamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained('orcamentos')->onDelete('cascade');
            $table->foreignId('categoria_despesa_id')->constrained('categorias_despesa');
            $table->foreignId('processo_id')->nullable()->constrained('processos');
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
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Garante que não haja duplicidade de alocação
            $table->unique(['orcamento_id', 'categoria_despesa_id', 'processo_id'], 'alocacao_unique');
        });

        // 5. Movimentações de Orçamento
        Schema::create('movimentacoes_orcamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alocacao_id')->constrained('alocacoes_orcamento')->onDelete('cascade');
            $table->enum('tipo', ['Empenho', 'Liquidação', 'Pagamento', 'Cancelamento']);
            $table->decimal('valor', 15, 2);
            $table->string('numero_documento')->nullable();
            $table->date('data_movimentacao');
            $table->text('observacoes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movimentacoes_orcamento');
        Schema::dropIfExists('alocacoes_orcamento');
        Schema::dropIfExists('categorias_despesa');
        Schema::dropIfExists('orcamentos');
        Schema::dropIfExists('fontes_orcamento');
    }
};
