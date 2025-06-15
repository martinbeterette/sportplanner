<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Superficie extends Model
{
    use SoftDeletes;
    protected $table = 'superficie';
    protected $fillable = ['descripcion'];

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'rela_superficie');
    }
}
