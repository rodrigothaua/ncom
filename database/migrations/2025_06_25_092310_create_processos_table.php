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
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo');
            $table->text('descricao')->nullable();
            $table->string('requisitante');
            $table->date('data_entrada');
            $table->timestamps();
            $table->softDeletes();
            
            // User tracking columns
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');

            // Índices
            $table->index('numero_processo');
            $table->index('data_entrada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processos');
    }
};
