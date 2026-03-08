<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DespesaVeiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'despesas_veiculo';

    protected $fillable = [
        'user_id', 'veiculo_id', 'tipo', 'descricao', 'data',
        'valor_centavos', 'tipo_uso', 'observacao',
    ];

    protected $casts = [
        'user_id' => 'integer','data' => 'date'];

    public static array $tipos = [
        'multa' => 'Multa',
        'pedagio' => 'Pedágio',
        'seguro' => 'Seguro',
        'estacionamento' => 'Estacionamento',
        'lavagem' => 'Lavagem',
        'licenciamento' => 'Licenciamento',
        'reboque' => 'Reboque',
        'outro' => 'Outro',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function getTipoLabelAttribute(): string
    {
        return self::$tipos[$this->tipo] ?? ucfirst($this->tipo);
    }

    public function getValorReaisAttribute(): float
    {
        return $this->valor_centavos / 100;
    }
}
