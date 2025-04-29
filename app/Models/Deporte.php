<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deporte extends Model
{
    protected $table = 'deporte';
    protected $fillable = ['descripcion', 'activo'];

    public function tipoDeportes() 
    {
        return $this->hasMany(tipoDeporte::class, 'rela_deporte');
    }
}
