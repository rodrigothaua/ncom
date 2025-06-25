<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detalhes_despesa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorias_id')->constrained('categorias');
            
            // Material de Consumo
            $table->string('pa_consumo')->nullable();
            $table->string('nd_consumo')->nullable();
            
            // Material Permanente
            $table->string('pa_permanente')->nullable();
            $table->string('nd_permanente')->nullable();
            
            // Serviços
            $table->string('pa_servico')->nullable();
            $table->string('nd_servico')->nullable();

            // User tracking
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalhes_despesa');
    }
};
