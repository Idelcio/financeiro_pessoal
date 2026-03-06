<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoFixoPagamento extends Model
{
    use HasFactory;

    protected $table = 'gastos_fixos_pagamentos';

    protected $fillable = [
        'gasto_fixo_id', 'user_id', 'valor_pago_centavos',
        'data_pagamento', 'mes_referencia', 'observacao',
    ];

    protected $casts = ['data_pagamento' => 'date'];

    public function gastoFixo()
    {
        return $this->belongsTo(GastoFixo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getValorPagoReaisAttribute(): float
    {
        return $this->valor_pago_centavos / 100;
    }
}
