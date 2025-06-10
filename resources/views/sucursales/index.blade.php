@extends('base') {{-- o el layout que uses --}}

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Nuestras Sucursales</h2>
    
    <div class="row">
        @foreach ($sucursales as $sucursal)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-2-strong">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title">{{ $sucursal->nombre }}</h5>
                            <p class="card-text">{{ $sucursal->direccion }}</p>
                        </div>
                        <a href="{{ url('/sucursales/' . $sucursal->id) }}" class="btn btn-primary mt-3">Ver sucursal</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
