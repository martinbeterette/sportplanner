<?php

namespace App\Http\Controllers;

use App\Models\TipoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoContactoController extends Controller
{
    //
    public function index(Request $request)
    {
        $tipoContactos = TipoContacto::all();

        if ($request->wantsJson()) {
            return response()->json($tipoContactos);
        } else {
            return response()->json($tipoContactos);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion'           => 'required|string',
        ], [
            'descripcion.required'  => 'La descripcion es requerida',
            'descripcion.string'    => 'La descripcion debe ser un texto',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json($validator->errors(), 400);
            }  
            return response()->json($validator->errors(), 400);      
        }

        $tipoContacto = TipoContacto::create([
            'descripcion'            => $request->descripcion,
        ]);

        if (!$tipoContacto) {
            if ($request->wantsJson()) {
            }
            return response()->json(['message' => 'Error al crear el tipo de contacto'], 500);
        }

        if ($request->wantsJson()) {
        }
        return response()->json($tipoContacto, 201);
    }
}
