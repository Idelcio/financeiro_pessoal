<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cartao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cartoes';

    protected $fillable = [
        'user_id', 'nome', 'bandeira', 'limite_centavos',
        'dia_fechamento', 'dia_vencimento', 'cor', 'tipo', 'ativo',
    ];

    protected $casts = [
        'user_id' => 'integer','ativo' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gastos()
    {
        return $this->hasMany(CartaoGasto::class);
    }

    public function totalDevidoCentavos(): int
    {
        return $this->gastos()
            ->with('parcelas')
            ->get()
            ->sum(fn($g) => $g->parcelas->where('pago', false)->sum('valor_centavos'));
    }
}
