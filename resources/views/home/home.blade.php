@extends('base')

@section('Title', 'Home')

@section('extra_stylesheets')
    <style>
        .welcome-card {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
        }
    </style>
@endsection

{% block content %}
    <div class="row">
        <div class="col-md-12">
            <div class="card welcome-card p-4 shadow-sm">
                <h2>Bienvenido a Mi Sistema</h2>
                <p class="mb-0">Gestiona usuarios, reservas y más desde este panel centralizado.</p>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Tarjeta de usuarios -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x text-primary"></i>
                    <h5 class="mt-3">Usuarios</h5>
                    <p>Administra los usuarios del sistema.</p>
                    <a href="/usuarios" class="btn btn-primary">Ir a Usuarios</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de reservas -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-alt fa-3x text-success"></i>
                    <h5 class="mt-3">Reservas</h5>
                    <p>Consulta y gestiona las reservas activas.</p>
                    <a href="/reservas" class="btn btn-success">Ver Reservas</a>
                </div>
            </div>
        </div>

        <!-- Tarjeta de reportes -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-chart-line fa-3x text-warning"></i>
                    <h5 class="mt-3">Reportes</h5>
                    <p>Visualiza estadísticas y reportes detallados.</p>
                    <a href="/reportes" class="btn btn-warning">Ver Reportes</a>
                </div>
            </div>
        </div>
    </div>
{% endblock %}

{% block extra_js %}
    <script>
        console.log("Script específico de la página de inicio");
    </script>
{% endblock %}