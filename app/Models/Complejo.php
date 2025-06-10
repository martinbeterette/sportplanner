<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complejo extends Model
{
    protected $table = 'complejo';
    protected $fillable = ['nombre', 'logo'];
    //
    public function sucursales() 
    {
        return $this->hasMany(Sucursal::class, 'rela_sucursal');
    }
}
