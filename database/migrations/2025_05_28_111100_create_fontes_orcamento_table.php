<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fontes_orcamento', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('codigo')->unique();
            $table->enum('tipo', ['Federal', 'Estadual', 'Municipal', 'Emenda Parlamentar', 'Outro'])->default('Federal');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fontes_orcamento');
    }
};
