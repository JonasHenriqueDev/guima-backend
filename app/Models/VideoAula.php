<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class VideoAula extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'titulo',
        'descricao',
        'video_id',
        'ordem',
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
