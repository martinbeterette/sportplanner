<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoDeporte extends Model
{
    //
    protected $table = 'tipo_deporte';
    protected $fillable = ['descripcion', 'activo', 'rela_deporte'];

    public function deporte() 
    {
        return $this->belongsTo(Deporte::class, 'rela_deporte');
    }


}
