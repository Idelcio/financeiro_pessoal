<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartaoParcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'cartao_gasto_id', 'user_id', 'numero_parcela',
        'valor_centavos', 'mes_referencia', 'pago', 'data_pagamento',
    ];

    protected $casts = [
        'pago' => 'boolean',
        'data_pagamento' => 'date',
    ];

    public function cartaoGasto()
    {
        return $this->belongsTo(CartaoGasto::class, 'cartao_gasto_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getValorReaisAttribute(): float
    {
        return $this->valor_centavos / 100;
    }
}
