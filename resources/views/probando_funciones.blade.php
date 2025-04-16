@extends('base')

@section('title', 'Tabla Personas')

@section('extra_stylesheets')
    
@endsection

@section('content')
    <h2 class="text-center my-4">Tabla de Personas</h2>
    <div id="tabla-container" class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle mb-0 bg-white">
            <thead class="table-dark">
                <th>NOMBRE</th>
                <th>APELLIDO</th>
                <th>FECHA DE NACIMIENTO</th>
            </thead>
            <tbody id="table-body"></tbody>
        </table>
    </div>
    <div id="paginator" class="d-flex justify-content-center mt-4"></div>
@endsection

@section('extra_js')
    <script src="{{ asset('js/utils.js') }}"></script>
    <script>
        let url = '/api/personas';
        let data = {registros_por_pagina:5};
        let campos = ['nombre', 'apellido', 'fecha_nacimiento'];
        let page = 1; // Página inicial
    </script>
    <script src="{{ asset('js/masterTableRender.js') }}"></script>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderTable('/api/personas', {}, ['nombre', 'apellido', 'fecha_nacimiento']);
        });
    </script> --}}
@endsection

