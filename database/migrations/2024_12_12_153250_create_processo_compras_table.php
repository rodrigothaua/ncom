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
            $table->string('descricao');
            //$table->date('data_vigente');
            $table->date('data_inicio');
            $table->date('data_vencimento');
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
