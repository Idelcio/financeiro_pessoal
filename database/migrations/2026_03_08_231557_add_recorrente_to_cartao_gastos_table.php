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
        Schema::table('cartao_gastos', function (Blueprint $table) {
            $table->boolean('recorrente')->default(false)->after('tipo');
            $table->boolean('recorrente_ativa')->default(true)->after('recorrente');
        });
    }

    public function down(): void
    {
        Schema::table('cartao_gastos', function (Blueprint $table) {
            $table->dropColumn(['recorrente', 'recorrente_ativa']);
        });
    }
};
