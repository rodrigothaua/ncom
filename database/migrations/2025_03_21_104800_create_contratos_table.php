<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained('processos')->onDelete('cascade');
            $table->string('numero_contrato');
            $table->decimal('valor_contrato', 10, 2);
            $table->date('data_inicial_contrato');
            $table->date('data_final_contrato');
            $table->text('observacoes')->nullable();
            $table->string('nome_empresa_contrato')->nullable();
            $table->string('cnpj_contrato', 20)->nullable();
            $table->string('numero_telefone_contrato', 20)->nullable();
            $table->timestamps();
            
            $table->index('processo_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contratos');
    }
};
