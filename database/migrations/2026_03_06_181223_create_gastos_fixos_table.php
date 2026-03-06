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
        Schema::create('gastos_fixos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->string('tipo_gasto'); // luz, agua, telefone, streaming, outro
            $table->integer('valor_centavos'); // armazenado em centavos
            $table->tinyInteger('dia_vencimento'); // 1-31
            $table->string('tipo')->default('pessoal'); // pessoal, empresa
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('gastos_fixos');
    }
};
