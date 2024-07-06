<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'plano',
        'vencimento',
        'status',
        'is_new_user',
    ];

    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function treino()
    {
        return $this->hasMany(Treino::class);
    }

    public function dieta()
    {
        return $this->hasMany(Dieta::class);
    }

    public function avaliacao()
    {
        return $this->hasMany(Avaliacao::class);
    }
}
