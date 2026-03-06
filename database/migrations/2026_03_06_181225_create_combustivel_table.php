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
        Schema::create('combustivel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('data_abastecimento');
            $table->string('tipo_combustivel')->default('gasolina'); // gasolina, etanol, diesel, gnv
            $table->decimal('litros', 8, 3)->nullable();
            $table->integer('valor_total_centavos');
            $table->integer('valor_litro_centavos')->nullable();
            $table->integer('km_atual')->nullable();
            $table->string('posto')->nullable();
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
        Schema::dropIfExists('combustivel');
    }
};
