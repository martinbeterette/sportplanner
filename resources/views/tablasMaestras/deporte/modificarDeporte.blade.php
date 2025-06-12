@extends('base')

@section('title', 'Tabla Deportes')

@section('extra_stylesheets')
    
@endsection

@section('content')
	<div class="container mt-4">
        <h2 class="mb-4">Modificar Deporte de Usuario</h2>
        
        <form action="{{ route('deporte.update', ['id' => $deporte->id]) }}" method="POST">
            @csrf
            @method('PUT') {{-- Para que Laravel entienda que es una actualización --}}
            
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="descripcion" 
                    name="descripcion" 
                    value="{{ old('descripcion', $deporte->descripcion) }}" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('deporte.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>    
@endsection

@section('extra_js')

@endsection
