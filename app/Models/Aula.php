<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Aula extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'titulo',
        'descricao',
        'url_id',
        'img_reference',
        'ordem',
        'submodulo_id',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
