<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplejoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear el complejo
        $complejoId = DB::table('complejo')->insertGetId([
            'nombre' => 'LeClub',
            'logo' => 'uploads/logos/leclub.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear las dos sucursales
        $sucursal1Id = DB::table('sucursal')->insertGetId([
            'nombre' => 'LeClub1',
            'direccion' => 'Av. Siempre Viva 123',
            'rela_complejo' => $complejoId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sucursal2Id = DB::table('sucursal')->insertGetId([
            'nombre' => 'LeClub2',
            'direccion' => 'Calle Falsa 456',
            'rela_complejo' => $complejoId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Suponemos que tipo_deporte_id = 1, superficie_id = 1, tipo_zona_id = 1 ya existen
        // Crear zona en LeClub1
        DB::table('zona')->insert([
            'descripcion' => 'Cancha Principal LeClub1',
            'rela_tipo_deporte' => 1,
            'rela_superficie' => 1,
            'rela_estado_zona' => 1, // Asumiendo que el estado "Buen Estado" tiene ID 1
            'rela_tipo_zona' => 1,
            'rela_sucursal' => $sucursal1Id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear zona en LeClub2
        DB::table('zona')->insert([
            'descripcion' => 'Cancha Principal LeClub2',
            'rela_tipo_deporte' => 1,
            'rela_superficie' => 1,
            'rela_estado_zona' => 2, // Asumiendo que el estado "Mal estado" tiene ID 2
            'rela_tipo_zona' => 1,
            'rela_sucursal' => $sucursal2Id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
