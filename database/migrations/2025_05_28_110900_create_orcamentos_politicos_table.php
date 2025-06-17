<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orcamentos_politicos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_orcamento')->unique();
            $table->string('fonte_recurso');
            $table->decimal('valor_total', 15, 2);
            $table->string('ano_exercicio');
            $table->text('descricao')->nullable();
            $table->enum('tipo', ['Federal', 'Estadual', 'Municipal'])->default('Federal');
            $table->enum('status', ['Em Análise', 'Aprovado', 'Reprovado', 'Em Execução', 'Concluído'])->default('Em Análise');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orcamentos_politicos');
    }
};
