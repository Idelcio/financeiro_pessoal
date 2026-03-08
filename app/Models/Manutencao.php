<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manutencao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'manutencoes';

    protected $fillable = [
        'user_id', 'veiculo_id', 'tipo', 'descricao', 'data',
        'km_na_manutencao', 'valor_centavos', 'oficina',
        'proxima_km', 'proxima_data', 'alerta_enviado', 'tipo_uso', 'observacao',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'data' => 'date',
        'proxima_data' => 'date',
        'alerta_enviado' => 'boolean',
    ];

    public static array $tipos = [
        'troca_oleo' => 'Troca de Óleo',
        'filtro_ar' => 'Filtro de Ar',
        'filtro_combustivel' => 'Filtro de Combustível',
        'filtro_cabine' => 'Filtro de Cabine',
        'pneu' => 'Pneu',
        'freio' => 'Freio',
        'correia' => 'Correia Dentada',
        'bateria' => 'Bateria',
        'revisao' => 'Revisão Geral',
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
        return ($this->valor_centavos ?? 0) / 100;
    }

    // Alerta: manutenção próxima por km
    public function getAlertaKmAttribute(): bool
    {
        if (!$this->proxima_km || !$this->veiculo?->km_atual) return false;
        return $this->veiculo->km_atual >= ($this->proxima_km - 1000);
    }

    // Alerta: manutenção próxima por data (30 dias)
    public function getAlertaDataAttribute(): bool
    {
        if (!$this->proxima_data) return false;
        return now()->diffInDays($this->proxima_data, false) <= 30;
    }
}
