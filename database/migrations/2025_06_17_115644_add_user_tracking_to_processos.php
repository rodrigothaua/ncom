<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('processos', function (Blueprint $table) {
            // Adiciona campos para rastreamento de usuários
            $table->foreignId('created_by')->after('id')->constrained('users');
            $table->foreignId('updated_by')->after('created_by')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('processos', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
            $table->dropColumn(['created_by', 'updated_by']);
        });
    }
};
