<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tipoContacto extends Model
{
    //
    protected $table = 'tipo_contacto';
    protected $fillable = ['descripcion', 'activo'];

    public function contactos() 
    {
        return $this->hasMany(Contacto::class, 'rela_tipo_contacto');
    }
}
