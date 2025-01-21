<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProcessoComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processo_compras', function (Blueprint $table) {
            if (!Schema::hasColumn('processo_compras', 'nome')) {
                $table->string('nome');
            }
    
            if (!Schema::hasColumn('processo_compras', 'categoria')) {
                $table->string('categoria');
            }
    
            if (!Schema::hasColumn('processo_compras', 'valor_total')) {
                $table->decimal('valor_total', 15, 2);
            }
    
            if (!Schema::hasColumn('processo_compras', 'data_inicio')) {
                $table->date('data_inicio');
            }
    
            if (!Schema::hasColumn('processo_compras', 'data_fim')) {
                $table->date('data_fim');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
