<?php

namespace App\Http\Controllers;

use App\Models\TipoContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TipoContactoController extends Controller
{
    private $model = TipoContacto::class;
    private $table = "tipo_contacto";
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
        $query  = $this->model::query();
        // $query = TipoContacto::where('activo', true);
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
        $tipoContacto = TipoContacto::find($id);

        if (!$tipoContacto) {
            return response()->json(['message' => 'Tipo de contacto no encontrado'], 404);
        }

        return response()->json($tipoContacto,201);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'descripcion' => "required|unique:{$this->table},descripcion|string|max:50",
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

        $objeto = $this->model::create([
            "descripcion" => $request->descripcion,
        ]);

        if (!$objeto) {
            return response()->json(['message' => "Error al crear el registro"], 500);
        }

        return redirect()->route("{$this->table}.index")->with('success', true);
    }
}
