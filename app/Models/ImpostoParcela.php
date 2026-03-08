<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpostoParcela extends Model
{
    use HasFactory;

    protected $fillable = [
        'imposto_id', 'user_id', 'numero_parcela',
        'valor_centavos', 'data_vencimento', 'pago', 'data_pagamento',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'pago' => 'boolean',
        'data_vencimento' => 'date',
        'data_pagamento' => 'date',
    ];

    public function imposto()
    {
        return $this->belongsTo(Imposto::class);
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
