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
        Schema::create('impostos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome'); // ex: IPVA - Civic, IPTU - Apartamento
            $table->string('tipo'); // ipva, iptu
            $table->integer('ano');
            $table->integer('valor_total_centavos');
            $table->tinyInteger('total_parcelas')->default(1);
            $table->string('descricao_bem')->nullable(); // ex: Honda Civic 2020, Apto Rua X
            $table->string('tipo_uso')->default('pessoal'); // pessoal, empresa
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impostos');
    }
};
