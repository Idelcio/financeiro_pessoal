<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Veiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'nome', 'marca', 'modelo', 'ano', 'placa', 'cor',
        'km_atual', 'tipo_combustivel', 'observacao', 'ativo',
    ];

    protected $casts = [
        'user_id' => 'integer','ativo' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function combustiveis()
    {
        return $this->hasMany(Combustivel::class);
    }

    public function manutencoes()
    {
        return $this->hasMany(Manutencao::class);
    }

    public function despesas()
    {
        return $this->hasMany(DespesaVeiculo::class);
    }

    public function impostos()
    {
        return $this->hasMany(Imposto::class);
    }

    // Consumo médio calculado em km/l
    public function getConsumMedioAttribute(): ?float
    {
        $abastecimentos = $this->combustiveis()
            ->whereNotNull('km_atual')
            ->whereNotNull('litros')
            ->orderBy('km_atual')
            ->get();

        if ($abastecimentos->count() < 2) {
            return null;
        }

        $kmTotal = $abastecimentos->last()->km_atual - $abastecimentos->first()->km_atual;
        $litrosTotal = $abastecimentos->skip(1)->sum('litros'); // ignora o primeiro (não sabe de onde veio)

        if ($litrosTotal <= 0) return null;

        return round($kmTotal / $litrosTotal, 2);
    }

    // Custo total do veículo (combustível + manutenção + despesas)
    public function getCustoTotalCentavosAttribute(): int
    {
        $combustivel = $this->combustiveis()->sum('valor_total_centavos');
        $manutencao = $this->manutencoes()->sum('valor_centavos');
        $despesas = $this->despesas()->sum('valor_centavos');
        return $combustivel + $manutencao + $despesas;
    }

    // Nome exibição completo
    public function getNomeCompletoAttribute(): string
    {
        $partes = array_filter([$this->marca, $this->modelo, $this->ano ? "({$this->ano})" : null]);
        return $this->nome . (count($partes) ? ' — ' . implode(' ', $partes) : '');
    }
}
