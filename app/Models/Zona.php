<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    protected $table = 'zona';
    protected $fillable = ['descripcion','dimension', 'rela_superficie', 'rela_tipo_zona','rela_tipo_deporte', 'activo', 'rela_sucursal'];
    //
    public function sucursales()
    {
        return $this->belongsTo(Sucursal::class, 'rela_sucursal');
    }

    public function tipoZona()
    {
        return $this->belongsTo(tipoZona::class, 'rela_tipo_zona');
    }

    public function superficie()
    {
        return $this->belongsTo(Superficie::class, 'rela_superficie');
    }
}
