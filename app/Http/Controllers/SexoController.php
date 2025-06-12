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

    public function indexApi(Request $request)
    {
        //iniciamos el query y filtro
        $query  = Sexo::query();
        // $query = sexo::where('activo', true);
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
        $totalRegistros     = $query->count();
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

    public function showApi($id)
    {
        $sexo = Sexo::find($id);

        if (!$sexo) {
            return response()->json(['message' => 'Tipo de contacto no encontrado'], 404);
        }

        return response()->json($sexo,201);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:sexo,descripcion|string|max:50',
        ],
        [
            'descripcion.required' => 'El campo es obligatorio',
            'descripcion.unique'   => 'El campo ya existe',
            'descripcion.string'   => 'El campo debe ser un texto',
            'descripcion.max'      => 'El campo es demaciado largo'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 400);
        }

        $sexo = Sexo::create([
            "descripcion" => $request->descripcion,
        ]);

        if (!$sexo) {
            return response()->json(['message' => 'Error al crear el sexo'], 500);
        }

        return redirect()->route('sexo.index')->with('success', true);
    }

    public function update(Request $request, $id)
    {
        // 1. Validación
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:sexo,descripcion',
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
            return redirect()->route('sexo.index')->with($data);
        }

        // 2. Buscar el sexo
        $sexo = Sexo::find($id);

        if (!$sexo) {
            $data = [
                "message" => "Error en la validación de los datos",
                "success" => false,
            ];
            return redirect()->route('sexo.index')->with($data);
        }

        // 3. Actualizar el campo
        $sexo->descripcion = $request->descripcion;
        $sexo->save(); // timestamps se actualizan solos

        // 4. Redirigir con mensaje de éxito
        return redirect()->route('sexo.index')->with('success', 'sexo actualizado correctamente');
    }

    public function destroy($id)
    {
        // 1. Buscar el sexo
        $sexo = Sexo::find($id);

        if (!$sexo) {
            $data = [
                "message" => "sexo no encontrado",
                "success" => false,
            ];
            return redirect()->route('sexo.index')->with($data);
        }

        // 2. "Eliminar" el sexo (soft delete )
        $sexo->delete();

        // 3. Redirigir con mensaje de éxito
        return redirect()->route('sexo.index')->with('success', 'Sexo eliminado correctamente');
    }

    public function create() 
    {
        return view('tablasMaestras.sexo.crearSexo');
    }

    public function edit($id) 
    {
        $sexo = Sexo::find($id);

        if (!$sexo) {
            return redirect()->route('sexo.index')->with('error', 'sexo no encontrado');
        }

        $data = [
            "sexo" => $sexo,
        ];

        return view('tablasMaestras.sexo.modificarSexo', $data);
    }

}
