<?php

namespace App\Http\Controllers;

use App\Models\EstadoZona;
use Illuminate\Http\Request;

class EstadoZonaController extends Controller
{
    private $model = EstadoZona::class;
    private $table = 'estado_zona';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(EstadoZona $estadoZona)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EstadoZona $estadoZona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstadoZona $estadoZona)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstadoZona $estadoZona)
    {
        //
    }
}
