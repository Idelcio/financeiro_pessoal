<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GastoAvulso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gastos_avulsos';

    protected $fillable = [
        'user_id', 'categoria_id', 'descricao',
        'valor_centavos', 'data', 'tipo', 'observacao',
    ];

    protected $casts = [
        'user_id' => 'integer','data' => 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function getValorReaisAttribute(): float
    {
        return $this->valor_centavos / 100;
    }
}
