<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Contacto;
use App\Models\Rol;
use App\Models\Persona;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        //VERIFICAR SI EXISTE EMAIL
        $persona = Persona::create([
            'nombre' => null,
            'apellido' => null,
            'fecha_nacimiento' => null,
            'rela_sexo' => null,
            'activo' => false,
        ]);

        $contacto = Contacto::create([
            'descripcion' => 'admin@admin.com',
            'rela_tipo_contacto' => 1,
            'rela_persona' => $persona->id,
            'activo' => false,
        ]);

        Usuario::create([
            'username' => 'admin0001',
            'rela_rol' => 1,
            'rela_contacto' => $contacto->id,
            'password' => Hash::make('admin0001'),
            'activo' => true,
        ]);

       
    }
}
