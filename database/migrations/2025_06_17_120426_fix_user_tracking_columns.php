<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Primeiro remover as constraints e colunas antigas se existirem
        Schema::table('processos', function (Blueprint $table) {
            // Remover foreign keys se existirem
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Remover colunas se existirem
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('orcamentos', function (Blueprint $table) {
            // Remover foreign keys se existirem
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            
            // Remover colunas se existirem
            $table->dropColumn(['created_by', 'updated_by']);
        });

        // Agora adicionar as colunas corretamente
        Schema::table('processos', function (Blueprint $table) {
            $table->foreignId('created_by')->after('id')->nullable()->constrained('users');
            $table->foreignId('updated_by')->after('created_by')->nullable()->constrained('users');
        });

        Schema::table('orcamentos', function (Blueprint $table) {
            $table->foreignId('created_by')->after('id')->nullable()->constrained('users');
            $table->foreignId('updated_by')->after('created_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        // Remover as novas colunas
        Schema::table('processos', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });

        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
