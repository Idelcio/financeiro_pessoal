<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receita extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'descricao', 'valor_centavos', 'data',
        'recorrente', 'tipo', 'observacao',
    ];

    protected $casts = [
        'user_id'    => 'integer',
        'data'       => 'date',
        'recorrente' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
