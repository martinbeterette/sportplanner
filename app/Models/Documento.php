<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    //
    use HasFactory;

    protected $table = 'documento';
    protected $fillable = ['descripcion', 'rela_persona', 'rela_tipo_documento', 'activo'];

    public function  persona()
    {
        return $this->belongsTo(Persona::class, 'rela_persona');
    }

    public function tipoDocumento()
    {
        return $this->belongsTo(TipoDocumento::class, 'rela_tipo_documento');
    }
}
