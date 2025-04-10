<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        TipoDocumento::create([
            'descripcion' => 'DNI',
            'activo' => true,
        ]);

        TipoDocumento::create([
            'descripcion' => 'Pasaporte',
            'activo' => true,
        ]);
        
        TipoDocumento::create([
            'descripcion' => 'Cédula de Identidad',
            'activo' => true,
        ]);
    }
}
