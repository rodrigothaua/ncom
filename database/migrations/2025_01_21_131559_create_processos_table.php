<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo')->unique();
            $table->text('descricao');
            $table->string('requisitante');
            $table->json('categoria'); // Agora aceita múltiplas categorias
            $table->decimal('valor_consumo', 10, 2)->nullable();
            $table->decimal('valor_permanente', 10, 2)->nullable();
            $table->decimal('valor_servico', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('processos');
    }
};
