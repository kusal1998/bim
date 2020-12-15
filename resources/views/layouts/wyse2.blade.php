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
    <link href="{{ asset('assets/fontawesome/css/all.min.css') }}" rel="stylesheet">
    <!-- vendor css -->

    <link href="{{ asset('lib/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/bundles/prism/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bundles/pretty-checkbox/pretty-checkbox.min.css') }}">
    <!-- DashForge CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dashforge.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashforge.dashboard.css') }}">



    <link id="dfMode" rel="stylesheet" href="{{ asset('assets/css/skin.cool.css') }}">
    <link id="dfSkin" rel="stylesheet" href="{{ asset('assets/css/skin.gradient1.css') }}">
    @yield('optional_css')
  </head>

  <body class="page-profile" >
        <header class="navbar navbar-header navbar-header-fixed">
          <a href="#" id="mainMenuOpen" class="burger-menu"><i data-feather="menu"></i></a>
          <div class="navbar-brand">
            <a href="/" class="df-logo">LAND<span>TITLE</span></a>
          </div><!-- navbar-brand -->
          <div id="navbarMenu" class="navbar-menu-wrapper">
            <div class="navbar-menu-header">
              <a href="" class="df-logo">LAND<span>TITLE</a>
              <a id="mainMenuClose" href="#"><i data-feather="x"></i></a>
            </div><!-- navbar-menu-header -->
            @include('layouts.components.navbar')
          </div><!-- navbar-menu-wrapper -->
          <div class="navbar-right">
                @include('layouts.components._messages')
                {{-- @include('layouts.components._notifications') --}}
                @include('layouts.components._profile')
          </div><!-- navbar-right -->
        </header><!-- navbar -->

        @yield('content')
        @yield('modals')
        <footer class="footer">
                <div>
                  <span>&copy; 2019 Bimsaviya v1.0.0. </span>
                  <!--<span>Created by <a href="">WyseLABS</a></span>-->
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
              <script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
              <script src="{{ asset('assets/js/dashforge.js') }}"></script>
              <script src="{{ asset('assets/js/dashforge.sampledata.js') }}"></script>
              <script src="{{ asset('assets/bundles/prism/prism.js') }}"></script>
              @yield('after_scripts')
              <!-- append theme customizer -->
              <script src="{{ asset('lib/js-cookie/js.cookie.js') }}"></script>


             {{--  <script>
                $(function(){
                  'use strict'

                  var plot = $.plot('#flotChart', [{
                    data: df3,
                    color: '#69b2f8'
                  },{
                    data: df1,
                    color: '#d1e6fa'
                  },{
                    data: df2,
                    color: '#d1e6fa',
                    lines: {
                      fill: false,
                      lineWidth: 1.5
                    }
                  }], {
                          series: {
                      stack: 0,
                              shadowSize: 0,
                      lines: {
                        show: true,
                        lineWidth: 0,
                        fill: 1
                      }
                          },
                    grid: {
                      borderWidth: 0,
                      aboveData: true
                    },
                          yaxis: {
                      show: false,
                              min: 0,
                              max: 350
                          },
                          xaxis: {
                      show: true,
                      ticks: [[0,''],[8,'Jan'],[20,'Feb'],[32,'Mar'],[44,'Apr'],[56,'May'],[68,'Jun'],[80,'Jul'],[92,'Aug'],[104,'Sep'],[116,'Oct'],[128,'Nov'],[140,'Dec']],
                      color: 'rgba(255,255,255,.2)'
                    }
                  });


                  $.plot('#flotChart2', [{
                    data: [[0,55],[1,38],[2,20],[3,70],[4,50],[5,15],[6,30],[7,50],[8,40],[9,55],[10,60],[11,40],[12,32],[13,17],[14,28],[15,36],[16,53],[17,66],[18,58],[19,46]],
                    color: '#69b2f8'
                  },{
                    data: [[0,80],[1,80],[2,80],[3,80],[4,80],[5,80],[6,80],[7,80],[8,80],[9,80],[10,80],[11,80],[12,80],[13,80],[14,80],[15,80],[16,80],[17,80],[18,80],[19,80]],
                    color: '#f0f1f5'
                  }], {
                    series: {
                      stack: 0,
                      bars: {
                        show: true,
                        lineWidth: 0,
                        barWidth: .5,
                        fill: 1
                      }
                    },
                    grid: {
                      borderWidth: 0,
                      borderColor: '#edeff6'
                    },
                    yaxis: {
                      show: false,
                      max: 80
                    },
                    xaxis: {
                      ticks:[[0,'Jan'],[4,'Feb'],[8,'Mar'],[12,'Apr'],[16,'May'],[19,'Jun']],
                      color: '#fff',
                    }
                  });

                  $.plot('#flotChart3', [{
                      data: df4,
                      color: '#9db2c6'
                    }], {
                          series: {
                              shadowSize: 0,
                      lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
                      }
                          },
                    grid: {
                      borderWidth: 0,
                      labelMargin: 0
                    },
                          yaxis: {
                      show: false,
                      min: 0,
                      max: 60
                    },
                          xaxis: { show: false }
                      });

                  $.plot('#flotChart4', [{
                      data: df5,
                      color: '#9db2c6'
                    }], {
                          series: {
                              shadowSize: 0,
                      lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
                      }
                          },
                    grid: {
                      borderWidth: 0,
                      labelMargin: 0
                    },
                          yaxis: {
                      show: false,
                      min: 0,
                      max: 80
                    },
                          xaxis: { show: false }
                      });

                  $.plot('#flotChart5', [{
                      data: df6,
                      color: '#9db2c6'
                    }], {
                          series: {
                              shadowSize: 0,
                      lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
                      }
                          },
                    grid: {
                      borderWidth: 0,
                      labelMargin: 0
                    },
                          yaxis: {
                      show: false,
                      min: 0,
                      max: 80
                    },
                          xaxis: { show: false }
                      });

                  $.plot('#flotChart6', [{
                      data: df4,
                      color: '#9db2c6'
                    }], {
                          series: {
                              shadowSize: 0,
                      lines: {
                        show: true,
                        lineWidth: 2,
                        fill: true,
                        fillColor: { colors: [ { opacity: 0 }, { opacity: .5 } ] }
                      }
                          },
                    grid: {
                      borderWidth: 0,
                      labelMargin: 0
                    },
                          yaxis: {
                      show: false,
                      min: 0,
                      max: 60
                    },
                          xaxis: { show: false }
                      });

                  $('#vmap').vectorMap({
                    map: 'usa_en',
                    showTooltip: true,
                    backgroundColor: '#fff',
                    color: '#d1e6fa',
                    colors: {
                      fl: '#69b2f8',
                      ca: '#69b2f8',
                      tx: '#69b2f8',
                      wy: '#69b2f8',
                      ny: '#69b2f8'
                    },
                    selectedColor: '#00cccc',
                    enableZoom: false,
                    borderWidth: 1,
                    borderColor: '#fff',
                    hoverOpacity: .85
                  });


                  var ctxLabel = ['6am', '10am', '1pm', '4pm', '7pm', '10pm'];
                  var ctxData1 = [20, 60, 50, 45, 50, 60];
                  var ctxData2 = [10, 40, 30, 40, 55, 25];

                  // Bar chart
                  var ctx1 = document.getElementById('chartBar1').getContext('2d');
                  new Chart(ctx1, {
                    type: 'horizontalBar',
                    data: {
                      labels: ctxLabel,
                      datasets: [{
                        data: ctxData1,
                        backgroundColor: '#69b2f8'
                      }, {
                        data: ctxData2,
                        backgroundColor: '#d1e6fa'
                      }]
                    },
                    options: {
                      maintainAspectRatio: false,
                      responsive: true,
                      legend: {
                        display: false,
                        labels: {
                          display: false
                        }
                      },
                      scales: {
                        yAxes: [{
                          gridLines: {
                            display: false
                          },
                          ticks: {
                            display: false,
                            beginAtZero: true,
                            fontSize: 10,
                            fontColor: '#182b49'
                          }
                        }],
                        xAxes: [{
                          gridLines: {
                            display: true,
                            color: '#eceef4'
                          },
                          barPercentage: 0.6,
                          ticks: {
                            beginAtZero: true,
                            fontSize: 10,
                            fontColor: '#8392a5',
                            max: 80
                          }
                        }]
                      }
                    }
                  });

                })
              </script> --}}
            </body>
          </html>
