<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contacto;

class ContactoController extends Controller
{
    //
    public function index()
    {
        $contactos = Contacto::all();
        return response()->json($contactos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|regex:/^[a-zA-Z0-9@._-]+$/',
            'rela_persona' => 'required|exists:persona,id',
            'rela_tipo_contacto' => 'required|exists:tipo_contacto,id',
        ],[
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'descripcion.regex' => 'El campo descripcion solo puede contener letras, números y los caracteres @ . _ -',
            'rela_persona.required' => 'El campo rela_persona es obligatorio',
            'rela_persona.exists' => 'El campo rela_persona no existe en la tabla persona',
            'rela_tipo_contacto.required' => 'El campo rela_tipo_contacto es obligatorio',
            'rela_tipo_contacto.exists' => 'El campo rela_tipo_contacto no existe en la tabla tipo_contacto',
        ]); 

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $contacto = Contacto::create([
            "descripcion" => $request->descripcion,
            "rela_persona" => $request->rela_persona,
            "rela_tipo_contacto" => $request->rela_tipo_contacto,
        ]);

        if (!$contacto) {
            return response()->json(['message' => 'Error al crear el contacto'], 500);
        }

        $contacto->load('persona', 'tipoContacto');

        return response()->json($contacto, 201);
    }
}
