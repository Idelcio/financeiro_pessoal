<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manutencoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('veiculo_id')->constrained('veiculos')->cascadeOnDelete();
            $table->string('tipo'); // troca_oleo, filtro_ar, filtro_combustivel, pneu, freio, revisao, correia, bateria, outro
            $table->string('descricao'); // detalhes do serviço
            $table->date('data');
            $table->integer('km_na_manutencao')->nullable();
            $table->integer('valor_centavos')->nullable();
            $table->string('oficina')->nullable();
            // alertas para próxima manutenção
            $table->integer('proxima_km')->nullable(); // km para próxima troca
            $table->date('proxima_data')->nullable(); // data prevista próxima
            $table->boolean('alerta_enviado')->default(false);
            $table->string('tipo_uso')->default('pessoal'); // pessoal, empresa
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manutencoes');
    }
};
