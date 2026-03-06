<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'nome', 'icone', 'cor'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gastosAvulsos()
    {
        return $this->hasMany(GastoAvulso::class);
    }
}
