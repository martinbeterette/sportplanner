<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sexo;
use Illuminate\Support\Facades\Validator;

class SexoController extends Controller
{
    //
    public function index() {
        $sexos = Sexo::all();
        return response()->json([
            "sexos"     => $sexos,
            "status"    => 201,
        ]);
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'descripcion'   => 'required|unique:sexo,descripcion',
            'activo'        => 'boolean',
        ],
        [
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'descripcion.unique'   => 'El campo descripcion debe ser único',
            'activo.boolean'       => 'El campo debe ser de valor booleano',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $rol = Sexo::create([
            "descripcion" => $request->descripcion,
        ]);

        if (!$rol) {
            return response()->json(['message' => 'Error al crear el rol'], 500);
        }

        return response()->json($rol, 201);
    }

    public function update() {

    }

    public function delete() {

    }

    public function partialUpdate() {

    }

}
