@extends('base')

@section('title', 'Tabla Roles')

@section('extra_stylesheets')
    
@endsection

@section('content')
    <h2 class="text-center my-4">Tabla de Roles</h2>
    
    {{-- Filtros --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="filtro" class="form-control" placeholder="Buscar...">
        </div>
        <div class="col-md-6">
            <select id="campo-filtro" class="form-select">
                <option value="id">ID</option>
                <option value="descripcion">Descripción</option>
            </select>
        </div>
    </div>

    {{-- Tabla --}}
    <div id="tabla-container" class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle mb-0 bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>DESCRIPCIÓN</th>
                </tr>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>
    <div id="paginator" class="d-flex justify-content-center mt-4"></div>
@endsection

@section('extra_js')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        let url = '/api/rol';
        let data = {registros_por_pagina: 5};
        let campos = ['id', 'descripcion'];
        let page = 1;
    </script>
    <script src="{{ asset('js/masterTableRender.js') }}"></script>
@endsection
