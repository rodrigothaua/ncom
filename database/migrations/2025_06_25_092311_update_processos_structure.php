<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Backup dados existentes
        $processos = DB::table('processos')->get();
        
        // Drop da tabela antiga
        Schema::dropIfExists('processos');

        // Criar nova estrutura
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

        // Restaurar dados
        if (!empty($processos)) {
            foreach ($processos as $processo) {
                DB::table('processos')->insert([
                    'id' => $processo->id,
                    'numero_processo' => $processo->numero_processo,
                    'descricao' => $processo->descricao,
                    'requisitante' => $processo->requisitante,
                    'data_entrada' => $processo->data_entrada,
                    'created_at' => $processo->created_at,
                    'updated_at' => $processo->updated_at
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Backup dados existentes
        $processos = DB::table('processos')->get();
        
        // Drop da tabela nova
        Schema::dropIfExists('processos');

        // Recriar estrutura antiga
        Schema::create('processos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_processo');
            $table->text('descricao')->nullable();
            $table->string('requisitante');
            $table->date('data_entrada');
            $table->timestamps();
        });

        // Restaurar dados
        if (!empty($processos)) {
            foreach ($processos as $processo) {
                DB::table('processos')->insert([
                    'id' => $processo->id,
                    'numero_processo' => $processo->numero_processo,
                    'descricao' => $processo->descricao,
                    'requisitante' => $processo->requisitante,
                    'data_entrada' => $processo->data_entrada,
                    'created_at' => $processo->created_at,
                    'updated_at' => $processo->updated_at
                ]);
            }
        }
    }
};
