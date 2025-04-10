<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    //
    protected $table = 'rol';
    protected $fillable = ['descripcion', 'activo'];
    
    public function usuarios() 
    {
        return $this->hasMany(Usuario::class, 'rela_rol');
    }
}
