<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaNumerosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_numeros', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('processo_id'); // Relacionado ao processo
            $table->enum('tipo', ['consumo', 'permanente', 'servico']); // Indica a categoria do PA
            $table->string('numero_pa'); // O número do PA
            $table->timestamps();
    
            // Chave estrangeira para vincular ao processo
            $table->foreign('processo_id')->references('id')->on('processos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pa_numeros');
    }
}
