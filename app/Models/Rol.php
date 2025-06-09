<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class rol extends Model
{
    // use SoftDeletes;
    //
    protected $table = 'rol';
    protected $fillable = ['descripcion', 'activo'];
    
    public function usuarios() 
    {
        return $this->hasMany(Usuario::class, 'rela_rol');
    }
}
