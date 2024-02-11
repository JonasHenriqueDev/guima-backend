<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Submodulo extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'titulo',
        'descricao',
        'ordem',
        'modulo_id',
        'img_capa'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function video_aulas()
    {
        return $this->hasMany(VideoAula::class);
    }

    
}
