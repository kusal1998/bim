<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Wysheit Technologies">
    <meta name="author" content="Wysheit Technologies">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.png">

    <title>LANDTITLE</title>
    <link href="{{ asset('assets/css/fonts.css') }}" rel="stylesheet">
    <!-- vendor css -->
    <link href="{{ asset('lib/%40fortawesome/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashforge.dashboard.css') }}">
  </head>

  <body class="page-profile">

        <header class="navbar navbar-header navbar-header-fixed">
          <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
          <div class="navbar-brand">
              <a href="/" class="df-logo">LAND<span>TITLE</span></a>
          </div><!-- navbar-brand -->
          <div id="navbarMenu" class="navbar-menu-wrapper">
            <div class="navbar-menu-header">
                <a href="/" class="df-logo">LAND<span>TITLE</span></a>
              <a id="mainMenuClose" href="#"><i data-feather="x"></i></a>
            </div><!-- navbar-menu-header -->
           
          </div><!-- navbar-menu-wrapper -->
          <div class="navbar-right">
               {{--  @include('layouts.components._profile') --}}
          </div><!-- navbar-right -->
        </header><!-- navbar -->

        @yield('content')

        <footer class="footer">
                <div>
                  <span>&copy; 2019 Bimsaviya v1.0.0. </span>
                  <span>Created by <a href="">WyseLABS</a></span>
                </div>
                <div>
                  <nav class="nav">
                  
                   
                   
                  </nav>
                </div>
              </footer>
          
              <script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
              <script src="{{ asset('lib/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
              <script src="{{ asset('lib/feather-icons/feather.min.js') }}"></script>
              <script src="{{ asset('lib/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
              <script src="{{ asset('lib/jquery.flot/jquery.flot.js') }}"></script>
              <script src="{{ asset('lib/jquery.flot/jquery.flot.stack.js') }}"></script>
              <script src="{{ asset('lib/jquery.flot/jquery.flot.resize.js') }}"></script>
              <script src="{{ asset('lib/chart.js/Chart.bundle.min.js') }}"></script>
              <script src="{{ asset('lib/select2/js/select2.full.min.js') }}"></script>
              <script src="{{ asset('lib/jqvmap/jquery.vmap.min.js') }}"></script>
              <script src="{{ asset('lib/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
              <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
              <script src="{{ asset('assets/js/dashforge.js') }}"></script>
              <script src="{{ asset('assets/js/dashforge.sampledata.js') }}"></script>
              @yield('after_scripts')
              <!-- append theme customizer -->
              <script src="{{ asset('lib/js-cookie/js.cookie.js') }}"></script>
             
              
     
            </body>
          </html>
          