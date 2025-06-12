<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class DeporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Deporte::withTrashed()->get());
    }

    public function indexApi(Request $request)
    {
        //iniciamos el query y filtro
        $query  = Deporte::query();
        // $query = Rol::where('activo', true);
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
        $deporte = Deporte::find($id);

        if (!$deporte) {
            return response()->json(['message' => 'Tipo de contacto no encontrado'], 404);
        }

        return response()->json($deporte,201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:deporte,descripcion|string|max:50',
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

        $deporte = Deporte::create([
            "descripcion" => $request->descripcion,
        ]);

        if (!$deporte) {
            return response()->json(['message' => 'Error al crear el deporte'], 500);
        }

        return redirect()->route('deporte.index')->with('success', true);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Validación
        $validator = Validator::make($request->all(), [
            'descripcion' => 'required|unique:deporte,descripcion',
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
            return redirect()->route('deporte.index')->with($data);
        }

        // 2. Buscar el deporte
        $deporte = Deporte::find($id);

        if (!$deporte) {
            $data = [
                "message" => "Error en la validación de los datos",
                "success" => false,
            ];
            return redirect()->route('deporte.index')->with($data);
        }

        // 3. Actualizar el campo
        $deporte->descripcion = $request->descripcion;
        $deporte->save(); // timestamps se actualizan solos

        // 4. Redirigir con mensaje de éxito
        return redirect()->route('deporte.index')->with('success', 'deporte actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // 1. Buscar el rol
        $deporte = Deporte::find($id);

        if (!$deporte) {
            $data = [
                "message" => "deporte no encontrado",
                "success" => false,
            ];
            return redirect()->route('deporte.index')->with($data);
        }

        // 2. "Eliminar" el deporte (soft delete )
        $deporte->delete();

        // 3. Redirigir con mensaje de éxito
        return redirect()->route('deporte.index')->with('success', 'Deporte eliminado correctamente');
    }

    public function create() 
    {
        return view('tablasMaestras.deporte.crearDeporte');
    }

    public function edit($id) 
    {
        $deporte = Deporte::find($id);

        if (!$deporte) {
            return redirect()->route('deporte.index')->with('error', 'deporte no encontrado');
        }

        $data = [
            "deporte" => $deporte,
        ];

        return view('tablasMaestras.deporte.modificarDeporte', $data);
    }
}
