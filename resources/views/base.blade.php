<!DOCTYPE html>
<html lang="en">
<head>
    @if (!session('usuario'))
        <script>
            window.location.href = "{{ url('/login') }}";
        </script>
    @endif
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Bootstrap MDB CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />
    <!-- MDB Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb-icons.min.css" rel="stylesheet" />
    {{-- font awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Aquí irían los CSS y JS particulares de la página --}}
    @yield('extra_stylesheets')
</head>
<body>
    <div class="d-flex">
        {{-- Barra de navegación Sidebar --}}
        
        <aside class="bg-dark text-white p-3" style="width: 250px; height: 100vh; position: fixed;">
            @include('partials.sidebar_bootstrap')
        </aside>
       

        {{-- Contenido principal --}}
        <div class="flex-grow-1" style="margin-left: 250px; padding: 20px;">
            {{-- Header --}}
            <main class="container-mt-4">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Footer --}}

    <footer class="bg-light text-center py-3 mt-4">
        @include('partials.footer')
    </footer>


    <!-- Bootstrap MDB JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.6/dist/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- VUE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/3.5.13/vue.global.min.js"></script>
    <!-- AXIOS -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- Aquí irían los JS particulares de la página --}}
    @yield('extra_js')
</body>
</html>