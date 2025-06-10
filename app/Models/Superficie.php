<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Superficie extends Model
{
    protected $table = 'superficie';
    protected $fillable = ['descripcion'];

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'rela_superficie');
    }
}
