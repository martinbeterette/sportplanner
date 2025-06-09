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

    public function indexApi(Request $request)
    {
        //iniciamos el query y filtro
        // $query  = Rol::query();
        $query = Rol::where('activo', true);
        $filtro = $request->filtro ?? null;
        
        //si el filtro no es vacio, lo aplicamos
        if(!empty($filtro)){
            $campos = ["id", "descripcion"];
            $query->where(function($q) use ($filtro, $campos) {
                foreach ($campos as $campo) {
                    $q->orWhere($campo, 'LIKE', "%$filtro%");
                }
            });
        }

        //tomamos el Request y calculos del paginado
        $paginaActual       = $request->page ?? 1;
        $registrosPorPagina = $request->registros_por_pagina ?? 10;
        $offset             = ($paginaActual - 1) * $registrosPorPagina;
        $totalRegistros     = $query->get()->count();
        $totalPaginas       = ceil($totalRegistros / $registrosPorPagina);
        

        
        //ejecutamos el query
        $registros = $query
            ->offset($offset)
            ->limit($registrosPorPagina)
            ->orderBy('id', 'asc')
            ->get();

        // Preparamos la respuesta
        $data = (object)[
            "data"              => $registros,
            "total_registros"   => $totalRegistros,
            "pagina"            => $paginaActual,
            "total_paginas"     => $totalPaginas,
        ];

        //retornamos la respuesta
        return response()->json($data, 200);
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
        // 1. Validación
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:rol,descripcion',
        ],
        [
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'descripcion.unique'   => 'El campo descripcion debe ser único',
        ]);

        if ($validator->fails()) {
            $data = [
                "message" => "Error en la validación de los datos",
                "errors"  => $validator->errors()->all(),
                "success" => false,
            ];
            return redirect()->route('rol.index')->with($data);
        }

        // 2. Buscar el rol
        $rol = Rol::find($id);

        if (!$rol) {
            $data = [
                "message" => "Error en la validación de los datos",
                "success" => false,
            ];
            return redirect()->route('rol.index')->with($data);
        }

        // 3. Actualizar el campo
        $rol->descripcion = $request->descripcion;
        $rol->save(); // timestamps se actualizan solos

        // 4. Redirigir con mensaje de éxito
        return redirect()->route('rol.index')->with('success', 'Rol actualizado correctamente');
    }

    public function destroy($id)
    {
        // 1. Buscar el rol
        $rol = Rol::find($id);

        if (!$rol) {
            $data = [
                "message" => "Rol no encontrado",
                "success" => false,
            ];
            return redirect()->route('rol.index')->with($data);
        }

        // 2. "Eliminar" el rol (soft delete casero)
        $rol->activo = false;
        $rol->save();

        // 3. Redirigir con mensaje de éxito
        return redirect()->route('rol.index')->with('success', 'Rol eliminado correctamente');
    }

    public function create() 
    {
        return view('tablasMaestras.rol.crearRol');
    }

    public function edit($id) 
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return redirect()->route('rol')->with('error', 'Rol no encontrado');
        }

        $data = [
            "rol" => $rol,
        ];

        return view('tablasMaestras.rol.modificarRol', $data);
    }
}
