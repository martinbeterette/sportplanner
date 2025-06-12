<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class EstadoZona extends Model
{
    use SoftDeletes;
    protected $table = "estado_zona";
    protected $fillable = ['descripcion'];

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'rela_estado_zona');
    }
}
