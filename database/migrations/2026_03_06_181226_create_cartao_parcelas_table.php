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
        Schema::create('cartao_parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cartao_gasto_id')->constrained('cartao_gastos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('numero_parcela'); // ex: 1, 2, 3...
            $table->integer('valor_centavos');
            $table->string('mes_referencia'); // formato: 2026-03
            $table->boolean('pago')->default(false);
            $table->date('data_pagamento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartao_parcelas');
    }
};
