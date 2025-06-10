<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursal';
    protected $fillable = ['nombre', 'direccion', 'rela_complejo'];
    //
    public function complejo()
    {
        return $this->belongsTo(Complejo::class, 'rela_complejo');
    }

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'rela_sucursal');
    }
}
