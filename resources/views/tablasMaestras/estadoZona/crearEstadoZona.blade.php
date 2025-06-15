@extends('base')
@php
    $titulo = "Estados de las zonas";
    $table  = "estado_zona";
@endphp
@section('title', $titulo)

@section('extra_stylesheets')
    
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Crear {{ $titulo }}</h2>
        
        <form action="{{ route("$table.insert") }}" method="POST">
            @csrf
            @method('POST') {{-- Para que Laravel entienda que es una actualización --}}
            
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="descripcion" 
                    name="descripcion" 
                    value="{{ session('descripcion') ?? '' }}" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
            <a href="{{ route("$table.index") }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>    
@endsection

@section('extra_js')

@endsection
