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
        Schema::create('cartao_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cartao_id')->constrained('cartoes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('descricao');
            $table->integer('valor_total_centavos');
            $table->tinyInteger('total_parcelas')->default(1);
            $table->date('data_compra');
            $table->string('categoria')->nullable();
            $table->string('tipo')->default('pessoal'); // pessoal, empresa
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
        Schema::dropIfExists('cartao_gastos');
    }
};
