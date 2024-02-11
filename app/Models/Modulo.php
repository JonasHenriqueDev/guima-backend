<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Modulo extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'titulo',
        'descricao',
        'ordem',
        'img_reference'
    ];

    public function submodulos()
    {
        return $this->hasMany(Submodulo::class);
    }
}
