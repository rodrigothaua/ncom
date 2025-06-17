<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('emendas_parlamentares', function (Blueprint $table) {
            $table->id();
            $table->string('numero_emenda')->unique();
            $table->string('parlamentar');
            $table->string('partido');
            $table->decimal('valor_total', 15, 2);
            $table->string('ano_exercicio');
            $table->text('objeto')->nullable();
            $table->enum('status', ['Em Análise', 'Aprovada', 'Reprovada', 'Em Execução', 'Concluída'])->default('Em Análise');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('emendas_parlamentares');
    }
};
