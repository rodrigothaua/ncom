<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('processos');
        
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo', 191)->unique();
            $table->text('descricao');
            $table->string('requisitante', 500);
            $table->date('data_entrada')->nullable();
            $table->string('modalidade')->nullable();
            $table->string('procedimentos_auxiliares')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('processos');
    }
};
