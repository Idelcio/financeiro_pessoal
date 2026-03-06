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
        Schema::create('gastos_fixos_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gasto_fixo_id')->constrained('gastos_fixos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('valor_pago_centavos');
            $table->date('data_pagamento');
            $table->string('mes_referencia'); // formato: 2026-03
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos_fixos_pagamentos');
    }
};
