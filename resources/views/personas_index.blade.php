@extends('base')

@section('title', 'Lista de Personas')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Lista de Personas</h2>
    
    <div class="table-responsive">
        
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha de Nacimiento</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($personas as $persona)
                        <tr>
                            <td>{{ $persona->id }}</td>
                            <td>{{ $persona->nombre }}</td>
                            <td>{{ $persona->apellido }}</td>
                            <td>{{ date('d/m/Y', strtotime($persona->fecha_nacimiento)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
        </table>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $personas->links() }}
        </div>
        
        
    </div>
</div>
@endsection
