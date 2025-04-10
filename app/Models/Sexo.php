<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sexo extends Model
{
    //
    protected $table = 'sexo';
    protected $fillable = ['descripcion', 'activo'];

    public function personas() 
    {
        return $this->hasMany(Persona::class, 'rela_sexo');
    }

}
