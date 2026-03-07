<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('combustivel', function (Blueprint $table) {
            $table->foreignId('veiculo_id')->nullable()->after('user_id')->constrained('veiculos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('combustivel', function (Blueprint $table) {
            $table->dropForeign(['veiculo_id']);
            $table->dropColumn('veiculo_id');
        });
    }
};
