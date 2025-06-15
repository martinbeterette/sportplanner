<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deporte extends Model
{
    use SoftDeletes;
    protected $table = 'deporte';
    protected $fillable = ['descripcion', 'activo'];

    public function tipoDeportes() 
    {
        return $this->hasMany(TipoDeporte::class, 'rela_deporte');
    }
}
