<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessosComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processo_compras', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo');
            $table->text('descricao');
            $table->string('requisitante');
            $table->json('categorias'); // Agora aceita múltiplas categorias
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processo_compras');
    }
}
