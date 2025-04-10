<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoContacto;

class TipoContactoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        TipoContacto::create([
            'descripcion' => 'Email',
            'activo' => true,
        ]);

        TipoContacto::create([
            'descripcion' => 'Telefono',
            'activo' => true,
        ]);
    }
}
