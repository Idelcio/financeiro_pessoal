<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nome'); // apelido, ex: "Civic do trabalho"
            $table->string('marca')->nullable(); // Honda, Toyota...
            $table->string('modelo')->nullable(); // Civic, Corolla...
            $table->integer('ano')->nullable();
            $table->string('placa')->nullable();
            $table->string('cor')->nullable();
            $table->integer('km_atual')->nullable();
            $table->string('tipo_combustivel')->default('gasolina'); // gasolina, etanol, diesel, flex, gnv, eletrico
            $table->text('observacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('veiculos');
    }
};
