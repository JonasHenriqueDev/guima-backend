<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Protocolo extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'file_path',
        'justificativa',
    ];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
}
