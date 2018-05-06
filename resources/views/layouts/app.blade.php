<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" ng-app="contabilizateApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Contabilizate') }}</title>
    
    <!-- Bootstrap core CSS-->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom fonts for this template-->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom styles for this template-->
    <link href="{{ asset('sb-admin/sb-admin.css') }}" rel="stylesheet">
    <!-- Angular Material styles -->
    <link rel="stylesheet" href="{{ asset('node_modules/angular-material/angular-material.css') }}">
    <!-- Styles app -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body @if (Auth::guest())
    class="bg-dark" 
@endif ng-cloak>
    <div id="app"  ngCloak
    @if (Auth::guest())
        class="bg-dark" 
    @endif>
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            @if (Auth::check())
                <a class="navbar-brand" href="{{ route('home') }}">Contabilizate</a>
            @else
                <a class="navbar-brand" href="/">Contabilizate</a>
            @endif
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
            @if (Auth::check())
                <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
                    <li class="nav-item toggle-side" data-toggle="tooltip" data-placement="right" title="Dashboard">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fa fa-fw fa-dashboard"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item toggle-side" data-toggle="tooltip" data-placement="right" title="Usuarios">
                        <a class="nav-link" href="{{ route('usuarios.all') }}">
                            <i class="fa fa-users"></i>
                            <span class="nav-link-text">Usuarios</span>
                        </a>
                    </li>
                    <li class="nav-item toggle-side" data-toggle="tooltip" data-placement="right" title="Contribuyentes">
                        <a class="nav-link" href="{{ route('contribuyentes.all') }}">
                            <i class="fa fa-address-book"></i>
                            <span class="nav-link-text">Contribuyentes</span>
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav sidenav-toggler">
                    <li class="nav-item">
                        <a class="nav-link text-center" id="sidenavToggler">
                            <i class="fa fa-fw fa-angle-left"></i>
                        </a>
                    </li>
                </ul>
            @endif
                <ul class="navbar-nav ml-auto">
                    @if (Auth::guest())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                        <i class="fa fa-fw fa-sign-in"></i> Inicia Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                        <i class="fa fa-address-card"></i> Registro</a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-user"></i> {{ Auth::user()->name." ". Auth::user()->last_name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                                <i class="fa fa-fw fa-sign-out"></i>Cerrar Sesión
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </nav>
        @yield('content')
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Scripts sb-admin -->
    <script src="{{ asset('sb-admin/sb-admin.min.js') }}"></script>
    <!-- Angular scripts -->
    <script src="{{ asset('node_modules/angular/angular.min.js') }}"></script>
    <script src="{{ asset('node_modules/angular-animate/angular-animate.min.js') }}"></script>
    <script src="{{ asset('node_modules/angular-aria/angular-aria.min.js') }}"></script>
    <script src="{{ asset('node_modules/angular-messages/angular-messages.min.js') }}"></script>
    <!-- Angular material scripts -->
    <script src="{{ asset('node_modules/angular-material/angular-material.min.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/directivas.js') }}"></script>
    @yield('scripts')
</body>
</html>
