<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Combustivel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'combustivel';

    protected $fillable = [
        'user_id', 'veiculo_id', 'data_abastecimento', 'tipo_combustivel', 'litros',
        'valor_total_centavos', 'valor_litro_centavos', 'km_atual',
        'posto', 'tipo', 'observacao',
    ];

    protected $casts = [
        'user_id' => 'integer','data_abastecimento' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class);
    }

    public function getValorTotalReaisAttribute(): float
    {
        return $this->valor_total_centavos / 100;
    }
}
