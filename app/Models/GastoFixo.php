<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GastoFixo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gastos_fixos';

    protected $fillable = [
        'user_id', 'nome', 'tipo_gasto', 'valor_centavos',
        'dia_vencimento', 'tipo', 'ativo', 'observacao',
    ];

    protected $casts = [
        'user_id' => 'integer','ativo' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pagamentos()
    {
        return $this->hasMany(GastoFixoPagamento::class);
    }

    public function getValorReaisAttribute(): float
    {
        return $this->valor_centavos / 100;
    }

    public function pagamentoDoMes(string $mesReferencia): ?GastoFixoPagamento
    {
        return $this->pagamentos()->where('mes_referencia', $mesReferencia)->first();
    }
}
