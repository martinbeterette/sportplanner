<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Rol;

class RolController extends Controller
{
    //
    public function index()
    {
        $roles = Rol::all();

        return response()->json($roles, 200);
    }

    public function show($id)
    {
        $rol  = Rol::find($id);

        if (!$rol) {
            return response()->json(['message' => 'Rol no encontrado'], 404);
        }

        return response()->json($rol, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:rol,descripcion',
        ],
        [
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'descripcion.unique'   => 'El campo descripcion debe ser único',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $rol = Rol::create([
            "descripcion" => $request->descripcion,
        ]);

        if (!$rol) {
            return response()->json(['message' => 'Error al crear el rol'], 500);
        }

        return response()->json($rol, 201);
    }

    public function update(Request $request, $id)
    {
        return "Actualizar el rol con id: $id";
    }

    public function destroy($id)
    {
        return "Eliminar el rol con id: $id";
    }
}
