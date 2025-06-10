<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoZona extends Model
{
    protected $table = 'tipo_zona';
    protected $fillable = ['descripcion'];

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'rela_tipo_zona');
    }
}
