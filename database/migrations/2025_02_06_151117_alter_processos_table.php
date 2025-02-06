<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->decimal('valor_total', 10, 2)->nullable()->change();
            $table->date('data_inicio')->nullable()->change();
            $table->date('data_vencimento')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->decimal('valor_total', 10, 2)->nullable(false)->change();
            $table->date('data_inicio')->nullable(false)->change();
            $table->date('data_vencimento')->nullable(false)->change();
        });
    }

}
