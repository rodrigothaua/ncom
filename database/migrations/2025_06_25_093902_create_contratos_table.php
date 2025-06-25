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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processo_id')->constrained('processos');
            $table->string('numero_contrato');
            $table->string('empresa');
            $table->string('cnpj');
            $table->decimal('valor_total', 15, 2);
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->text('objeto')->nullable();
            $table->enum('status', ['Em Vigor', 'Vencido', 'Cancelado', 'Em Elaboração'])->default('Em Elaboração');
            $table->timestamps();
            $table->softDeletes();
            
            // User tracking
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');

            // Índices
            $table->index('numero_contrato');
            $table->index('cnpj');
            $table->index('data_fim');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
