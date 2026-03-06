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
        Schema::create('imposto_parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imposto_id')->constrained('impostos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('numero_parcela');
            $table->integer('valor_centavos');
            $table->date('data_vencimento');
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
        Schema::dropIfExists('imposto_parcelas');
    }
};
