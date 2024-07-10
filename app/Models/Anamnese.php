<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anamnese extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'cpf',
        'address',
        'email',
        'plano',
        'vencimento',
        'photo_reference',
        'idade',
        'peso',
        'altura',
        'objetivo',
        'is_aprovada',
        'campos_reprovados',
    ];
}
