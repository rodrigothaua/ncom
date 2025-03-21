<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('detalhes_despesa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorias_id')->constrained('categorias')->onDelete('cascade');
            $table->string('pa_consumo')->nullable();
            $table->string('pa_permanente')->nullable();
            $table->string('pa_servico')->nullable();
            $table->string('nd_consumo')->nullable();
            $table->string('nd_permanente')->nullable();
            $table->string('nd_servico')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('detalhes_despesa');
    }
};
