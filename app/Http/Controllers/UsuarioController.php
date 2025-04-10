<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    //
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios, 200);
    }

    public function show($id)
    {
        return "Detalle del usuario con id: $id";
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'      => 'required|unique:usuario,username',
            'password'      => 'required',
            'rela_contacto' => 'required|exists:contacto,id',
            'rela_rol'      => 'required|exists:rol,id',
        ],
        [
            'username.required' => 'El campo username es obligatorio',
            'username.unique' => 'El campo username debe ser único',
            'password.required' => 'El campo password es obligatorio',
            'rela_contacto.required' => 'El campo rela_contacto es obligatorio',
            'rela_contacto.exists' => 'El campo rela_contacto no existe en la tabla contacto',
            'rela_rol.required' => 'El campo rela_rol es obligatorio',
            'rela_rol.exists' => 'El campo rela_rol no existe en la tabla rol',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Error de validación",
                "errors" => $validator->errors()], 400);
        }

        $usuario = Usuario::create([
            "username"      => $request->username,
            "password"      => Hash::make($request->password),
            "rela_contacto" => $request->rela_contacto,
            "rela_rol"      => $request->rela_rol,
        ]);

        if (!$usuario) {
            return response()->json([
                "success" => false,
                'message' => 'Error al crear el usuario'], 500);
        }

        $usuario->load('contacto', 'rol');

        return response()->json([
            "success"       => true,
            "message"       => "Usuario creado correctamente",
            "usuario"       => $usuario,
        ], 201);
    }

    public function registrarUsuario(Request $request)
    {
        return response()->json([
            "success" => true,
            "usuario" => $request->all(),
        ], 200);
        $validator = Validator::make($request->all(), [
            'username'      => 'required|unique:usuario,username',
            'password'      => 'required',
            'rela_contacto' => 'required|exists:contacto,id',
            'rela_rol'      => 'required|exists:rol,id',
        ],
        [
            'username.required' => 'El campo username es obligatorio',
            'username.unique' => 'El campo username debe ser único',
            'password.required' => 'El campo password es obligatorio',
            'rela_contacto.required' => 'El campo rela_contacto es obligatorio',
            'rela_contacto.exists' => 'El campo rela_contacto no existe en la tabla contacto',
            'rela_rol.required' => 'El campo rela_rol es obligatorio',
            'rela_rol.exists' => 'El campo rela_rol no existe en la tabla rol',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Error de validación",
                "errors" => $validator->errors()], 400);
        }

        $usuario = Usuario::create([
            "username"      => $request->username,
            "password"      => Hash::make($request->password),
            "rela_contacto" => $request->rela_contacto,
            "rela_rol"      => $request->rela_rol,
        ]);

        if (!$usuario) {
            return response()->json([
                "success" => false,
                'message' => 'Error al crear el usuario'], 500);
        }

        $usuario->load('contacto', 'rol');

        return response()->json([
            "success"       => true,
            "message"       => "Usuario creado correctamente",
            "usuario"       => $usuario,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        return "Actualizar el usuario con id: $id";
    }

    public function destroy($id)
    {
        return "Eliminar el usuario con id: $id";
    }
}
