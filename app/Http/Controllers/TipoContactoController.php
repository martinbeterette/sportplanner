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

    public function indexApi(Request $request)
    {
        //iniciamos el query y filtro
        $query  = TipoContacto::where('activo', 1);
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
        $personas = $query
            ->offset($offset)
            ->limit($registrosPorPagina)
            ->orderBy('id', 'asc')
            ->get();

        $data = (object)[
            "data"              => $personas,
            "total_registros"   => $totalRegistros,
            "pagina"            => $paginaActual,
            "total_paginas"     => $totalPaginas,
        ];
        return response()->json($data);
    }

    public function showApi($id)
    {
        $tipoContacto = TipoContacto::find($id);

        if (!$tipoContacto) {
            return response()->json(['message' => 'Tipo de contacto no encontrado'], 404);
        }

        return response()->json($tipoContacto,201);
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
