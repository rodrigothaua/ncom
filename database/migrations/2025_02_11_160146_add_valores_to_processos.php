<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValoresToProcessos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->decimal('valor_consumo', 10, 2)->nullable();
            $table->decimal('valor_permanente', 10, 2)->nullable();
            $table->decimal('valor_servico', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->dropColumn(['valor_consumo', 'valor_permanente', 'valor_servico', 'valor_total']);
        });
    }
}
