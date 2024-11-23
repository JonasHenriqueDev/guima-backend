<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks';

    protected $fillable = [
        'motivo_reprovacao',

        'aluno_id',
        'is_aprovado',
        'descricao',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
