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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained('processos');
            $table->decimal('valor_consumo', 15, 2)->default(0);
            $table->decimal('valor_permanente', 15, 2)->default(0);
            $table->decimal('valor_servico', 15, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // User tracking columns
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');

            // Índices
            $table->index('processo_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
