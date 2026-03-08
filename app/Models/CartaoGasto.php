<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartaoGasto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'cartao_id', 'user_id', 'descricao', 'valor_total_centavos',
        'total_parcelas', 'data_compra', 'categoria', 'tipo', 'observacao',
    ];

    protected $casts = [
        'user_id' => 'integer','data_compra' => 'date'];

    public function cartao()
    {
        return $this->belongsTo(Cartao::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parcelas()
    {
        return $this->hasMany(CartaoParcela::class, 'cartao_gasto_id');
    }

    public function getValorTotalReaisAttribute(): float
    {
        return $this->valor_total_centavos / 100;
    }

    public function getValorParcelaReaisAttribute(): float
    {
        return ($this->valor_total_centavos / $this->total_parcelas) / 100;
    }
}
