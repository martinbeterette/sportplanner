<?php

namespace App\Http\Controllers;

use App\Models\Deporte;
use Illuminate\Http\Request;

class DeporteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function indexApi(Request $request)
    {
        //iniciamos el query y filtro
        $query  = Deporte::where('activo', 1);
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
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
