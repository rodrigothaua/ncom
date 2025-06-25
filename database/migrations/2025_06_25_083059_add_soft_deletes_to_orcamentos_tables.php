<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('fontes_orcamento', function (Blueprint $table) {
            if (!Schema::hasColumn('fontes_orcamento', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('categorias_despesa', function (Blueprint $table) {
            if (!Schema::hasColumn('categorias_despesa', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('orcamentos', function (Blueprint $table) {
            if (!Schema::hasColumn('orcamentos', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('alocacoes_orcamento', function (Blueprint $table) {
            if (!Schema::hasColumn('alocacoes_orcamento', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('movimentacoes_orcamento', function (Blueprint $table) {
            if (!Schema::hasColumn('movimentacoes_orcamento', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down()
    {
        Schema::table('fontes_orcamento', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('categorias_despesa', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('alocacoes_orcamento', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('movimentacoes_orcamento', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
