<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sexo;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Sexo::create([
            'descripcion' => 'Masculino',
            'activo' => true,
        ]);

        Sexo::create([
            'descripcion' => 'Femenino',
            'activo' => true,
        ]);

        Sexo::create([
            'descripcion' => 'No Binario',
            'activo' => true,
        ]);
        
        Sexo::create([
            'descripcion' => 'Otro',
            'activo' => true,
        ]);
    }
}
