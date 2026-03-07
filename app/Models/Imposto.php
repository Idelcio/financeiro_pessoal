<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Imposto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'veiculo_id', 'nome', 'tipo', 'ano', 'valor_total_centavos',
        'total_parcelas', 'descricao_bem', 'tipo_uso', 'observacao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function parcelas()
    {
        return $this->hasMany(ImpostoParcela::class);
    }

    public function getValorTotalReaisAttribute(): float
    {
        return $this->valor_total_centavos / 100;
    }

    public function getTotalPagoCentavosAttribute(): int
    {
        return $this->parcelas->where('pago', true)->sum('valor_centavos');
    }

    public function getTotalPendentesCentavosAttribute(): int
    {
        return $this->parcelas->where('pago', false)->sum('valor_centavos');
    }
}
