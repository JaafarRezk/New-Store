<!doctype html>
<html class="no-js" lang=""> <!--<![endif]-->
<head>
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$setting->website_title}}</title>
    <meta name="description" content="{{$setting->website_description}}">
    <meta name="viewport" content="width=device-width, initial-scale=1">


<link rel="apple-touch-icon" href="https://i.imgur.com/QRAUqs9.png">
<link rel="shortcut icon" href="https://i.imgur.com/QRAUqs9.png">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">


<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/css/cs-skin-elastic.css') }}" rel="stylesheet" />


   <style>
    #weatherWidget .currentDesc {
        color: #ffffff!important;
    }
        .traffic-chart {
            min-height: 335px;
        }
        #flotPie1  {
            height: 150px;
        }
        #flotPie1 td {
            padding:3px;
        }
        #flotPie1 table {
            top: 20px!important;
            right: -10px!important;
        }
        .chart-container {
            display: table;
            min-width: 270px ;
            text-align: left;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        #flotLine5  {
             height: 105px;
        }

        #flotBarChart {
            height: 150px;
        }
        #cellPaiChart{
            height: 160px;
        }

    </style>
</head>

<body>
    <!-- Left Panel -->
  
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="menu-icon fa fa-laptop"></i>Dashboard</a>
                </li>
                <li class="{{ Request::is('categories*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}"><i class="menu-icon fa fa-tags"></i>Categories</a>
                </li>
                <li class="{{ Request::is('products*') ? 'active' : '' }}">
                    <a href="{{ route('products.index') }}"><i class="menu-icon fa fa-cube"></i>Products</a>
                </li>
                <li class="{{ Request::is('colors*') ? 'active' : '' }}">
                    <a href="{{ route('colors.index') }}"><i class="menu-icon fa fa-paint-brush"></i>Colors</a>
                </li>
                <li class="{{ Request::is('users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}"><i class="menu-icon fa fa-users"></i>Users</a>
                </li>
                <li class="{{ Request::is('delivery*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-users.index') }}"><i class="menu-icon fa fa-truck"></i>Delivery</a>
                </li>
                <li class="{{ Request::is('variations*') ? 'active' : '' }}">
                    <a href="{{ route('variations.create') }}"><i class="menu-icon fa fa-random"></i>Variations</a>
                </li>
                

                <li>
                    <a href="{{ route('Reviews.show') }}">
                        <i class="menu-icon fa fa-comments"></i> Reviews
                    </a>
                </li>


                <li>
                    <a href="{{ route('discounts.index') }}">
                        <i class="menu-icon fas fa-percent"></i>Discount Management
                    </a>
                </li>
                <li>
                    <a href="{{ route('system_settings.index') }}">
                        <i class="menu-icon fa fa-cog"></i>Settings
                    </a>
                </li>
          
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
</aside>

 <!-- /#left-panel -->
    <!-- Right Panel -->
    <div class="right-panel" id="right-panel">
    <header id="header" class="header">
        <div class="top-left">
            <div class="navbar-header">
            {{$setting->website_name}}
                <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
            </div>
        </div>
        <div class="top-right">
            <div class="header-menu">
                <div class="header-left">
                    <button class="search-trigger"><i class="fa fa-search"></i></button>
                    <div class="form-inline">
                        <form class="search-form">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                            <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                        </form>
                    </div>
                  
                </div>

                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      
                            {{ Auth::user()->name }} <!-- هنا نستخدم القيمة Auth::user()->name لعرض اسم المستخدم -->
                            <img class="user-avatar rounded-circle" src="{{ asset('/images/default.png') }}" alt="User Avatar">
                        </a>
                                                

                      <div class="user-menu dropdown-menu">
                            <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fa fa-user"></i> My Profile</a>

                            <a class="nav-link" href="{{route('system_settings.index')}}"><i class="fa fa-cog"></i> Settings</a>
                            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i> Logout</a>

                            <form id="logout-form" action="{{ route('logout_submit') }}" method="post" style="display: none;">
                                @csrf
                            </form>

<script>
    // يُضاف هذا الكود الخاص بتحديث طريقة النموذج عند تغيير اللغة للتأكد من أنها تتطابق مع طريقة الاستدعاء الفعلية
    document.getElementById('logout-form').setAttribute('method', 'POST');
</script>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="content">
        <div class="container">
            <div class="row">
            <div class="col-xl-10 mt-4">
    <div class="table-responsive-sm">
        <table id="basic-datatable" class="table table-striped table-centered mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product</th>
                    <th>Color</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Size</th>
                    <th>Images</th> <!-- تمت إضافة عنوان الصور -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($variations as $variation)
                <tr>
                    <td>{{ $variation->id }}</td>
                    <td>
                        @foreach ($products as $product)
                        @if ($variation->product_id == $product->id)
                        {{ $product->name }}
                        @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($colors as $color)
                        @if ($variation->color_id == $color->id)
                        {{ $color->name }}
                        @endif
                        @endforeach
                    </td>
                    <td>{{ $variation->quantity }}</td>
                    <td>{{ $variation->price }}</td>
                    <td>{{ $variation->size }}</td>
                    <td>
                        @foreach($variation->images as $image) <!-- تمت إضافة حلقة لعرض الصور -->
                        <img src="{{ $image->url }}" alt="Variation Image" style="max-width: 100px;">
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('variations.edit', $variation->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('variations.delete', $variation->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<br><br><br>

<div class="col-xl-6">
    <div class="card">
        <div class="card-body">
            <h4 class="mb-3 header-title">Add New Variation</h4>
            <form action="{{ route('variations.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="product_id">Product</label>
                    <select name="product_id" id="product_id" class="form-control" required>
                        @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="color_id">Color</label>
                    <select name="color_id" id="color_id" class="form-control" required>
                        @foreach ($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="text" class="form-control" name="quantity" id="quantity" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" class="form-control" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label for="size">Size</label>
                    <select name="size" id="size" class="form-control" required>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                        <option value="M">M</option>
                        <option value="S">S</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="images">Images</label>
                    <input type="file" class="form-control" name="images[]" id="images" multiple accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Save Variation</button>
            </form>
        </div>
    </div>
</div>




<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="{{ asset('assets/js/main.js')}}"></script>

<!--  Chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.7.3/dist/Chart.bundle.min.js"></script>

<!--Chartist Chart-->
<script src="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartist-plugin-legend@0.6.2/chartist-plugin-legend.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/jquery.flot@0.8.3/jquery.flot.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flot-pie@1.0.0/src/jquery.flot.pie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flot-spline@0.0.1/js/jquery.flot.spline.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/simpleweather@3.1.0/jquery.simpleWeather.min.js"></script>
<script src="{{ asset('assets/js/init/weather-init.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/moment@2.22.2/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
<script src="{{ asset('assets/js/init/fullcalendar-init.js')}}"></script>

    <!--Local Stuff-->
    <script>
        jQuery(document).ready(function($) {
            "use strict";

            // Pie chart flotPie1
            var piedata = [
                { label: "Desktop visits", data: [[1,32]], color: '#5c6bc0'},
                { label: "Tab visits", data: [[1,33]], color: '#ef5350'},
                { label: "Mobile visits", data: [[1,35]], color: '#66bb6a'}
            ];

            $.plot('#flotPie1', piedata, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.65,
                        label: {
                            show: true,
                            radius: 2/3,
                            threshold: 1
                        },
                        stroke: {
                            width: 0
                        }
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });
            // Pie chart flotPie1  End
            // cellPaiChart
            var cellPaiChart = [
                { label: "Direct Sell", data: [[1,65]], color: '#5b83de'},
                { label: "Channel Sell", data: [[1,35]], color: '#00bfa5'}
            ];
            $.plot('#cellPaiChart', cellPaiChart, {
                series: {
                    pie: {
                        show: true,
                        stroke: {
                            width: 0
                        }
                    }
                },
                legend: {
                    show: false
                },grid: {
                    hoverable: true,
                    clickable: true
                }

            });
            // cellPaiChart End
            // Line Chart  #flotLine5
            var newCust = [[0, 3], [1, 5], [2,4], [3, 7], [4, 9], [5, 3], [6, 6], [7, 4], [8, 10]];

            var plot = $.plot($('#flotLine5'),[{
                data: newCust,
                label: 'New Data Flow',
                color: '#fff'
            }],
            {
                series: {
                    lines: {
                        show: true,
                        lineColor: '#fff',
                        lineWidth: 2
                    },
                    points: {
                        show: true,
                        fill: true,
                        fillColor: "#ffffff",
                        symbol: "circle",
                        radius: 3
                    },
                    shadowSize: 0
                },
                points: {
                    show: true,
                },
                legend: {
                    show: false
                },
                grid: {
                    show: false
                }
            });
            // Line Chart  #flotLine5 End
            // Traffic Chart using chartist
            if ($('#traffic-chart').length) {
                var chart = new Chartist.Line('#traffic-chart', {
                  labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                  series: [
                  [0, 18000, 35000,  25000,  22000,  0],
                  [0, 33000, 15000,  20000,  15000,  300],
                  [0, 15000, 28000,  15000,  30000,  5000]
                  ]
              }, {
                  low: 0,
                  showArea: true,
                  showLine: false,
                  showPoint: false,
                  fullWidth: true,
                  axisX: {
                    showGrid: true
                }
            });

                chart.on('draw', function(data) {
                    if(data.type === 'line' || data.type === 'area') {
                        data.element.animate({
                            d: {
                                begin: 2000 * data.index,
                                dur: 2000,
                                from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                                to: data.path.clone().stringify(),
                                easing: Chartist.Svg.Easing.easeOutQuint
                            }
                        });
                    }
                });
            }
            // Traffic Chart using chartist End
            //Traffic chart chart-js
            if ($('#TrafficChart').length) {
                var ctx = document.getElementById( "TrafficChart" );
                ctx.height = 150;
                var myChart = new Chart( ctx, {
                    type: 'line',
                    data: {
                        labels: [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul" ],
                        datasets: [
                        {
                            label: "Visit",
                            borderColor: "rgba(4, 73, 203,.09)",
                            borderWidth: "1",
                            backgroundColor: "rgba(4, 73, 203,.5)",
                            data: [ 0, 2900, 5000, 3300, 6000, 3250, 0 ]
                        },
                        {
                            label: "Bounce",
                            borderColor: "rgba(245, 23, 66, 0.9)",
                            borderWidth: "1",
                            backgroundColor: "rgba(245, 23, 66,.5)",
                            pointHighlightStroke: "rgba(245, 23, 66,.5)",
                            data: [ 0, 4200, 4500, 1600, 4200, 1500, 4000 ]
                        },
                        {
                            label: "Targeted",
                            borderColor: "rgba(40, 169, 46, 0.9)",
                            borderWidth: "1",
                            backgroundColor: "rgba(40, 169, 46, .5)",
                            pointHighlightStroke: "rgba(40, 169, 46,.5)",
                            data: [1000, 5200, 3600, 2600, 4200, 5300, 0 ]
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        tooltips: {
                            mode: 'index',
                            intersect: false
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        }

                    }
                } );
            }
            //Traffic chart chart-js  End
            // Bar Chart #flotBarChart
            $.plot("#flotBarChart", [{
                data: [[0, 18], [2, 8], [4, 5], [6, 13],[8,5], [10,7],[12,4], [14,6],[16,15], [18, 9],[20,17], [22,7],[24,4], [26,9],[28,11]],
                bars: {
                    show: true,
                    lineWidth: 0,
                    fillColor: '#ffffff8a'
                }
            }], {
                grid: {
                    show: false
                }
            });
            // Bar Chart #flotBarChart End
        });
    </script>

</body>
</html>
