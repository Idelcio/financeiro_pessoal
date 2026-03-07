<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('despesas_veiculo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('veiculo_id')->constrained('veiculos')->cascadeOnDelete();
            $table->string('tipo'); // multa, pedagio, seguro, estacionamento, lavagem, licenciamento, reboque, outro
            $table->string('descricao');
            $table->date('data');
            $table->integer('valor_centavos');
            $table->string('tipo_uso')->default('pessoal'); // pessoal, empresa
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('despesas_veiculo');
    }
};
