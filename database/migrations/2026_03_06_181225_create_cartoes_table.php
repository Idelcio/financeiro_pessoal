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
        Schema::create('cartoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome'); // ex: Nubank, Inter, Bradesco
            $table->string('bandeira')->nullable(); // visa, mastercard, elo, etc
            $table->integer('limite_centavos')->nullable();
            $table->tinyInteger('dia_fechamento')->nullable(); // dia do fechamento da fatura
            $table->tinyInteger('dia_vencimento')->nullable(); // dia do vencimento da fatura
            $table->string('cor')->default('#6366f1');
            $table->string('tipo')->default('pessoal'); // pessoal, empresa
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartoes');
    }
};
