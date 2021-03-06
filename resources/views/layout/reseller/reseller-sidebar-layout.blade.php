
 <!DOCTYPE html>
 <html lang="en">
 
 <head>
   <meta charset="utf-8" />
   <link rel="icon" type="image/png" href="/assets/img/betsylogo.png">
   <link rel="icon" type="image/png" href="/assets/img/betsylogo.png">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
   <title>
     Betsy - Vendor
   </title>

   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">
   
   <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
   <!--     Fonts and icons     -->
   <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
   <!-- CSS Files -->
   <link href="/assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
   <!-- CSS Just for demo purpose, don't include it in your project -->
   <link href="/assets/demo/demo.css" rel="stylesheet" />
   {{-- Material CSS --}}
   <link href="/assets/css/material-dashboard.min.css" rel="stylesheet" />
   {{-- JQuery UI --}}
   <link rel="stylesheet" href="/assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
   {{-- CUSTOM CSS --}}
   <link href="/assets/custom_scss/custom.css" rel="stylesheet" />
   {{-- DataTable --}}
   <link rel="stylesheet" type="text/css" href="/assets/datatable/datatables.min.css"/>
   {{-- Switchery --}}
   <link rel="stylesheet" href="/assets/switchery-master/dist/switchery.css" />
   {{-- SummerNote --}}
   <link rel="stylesheet" href="/assets/summernote-0.8.16/summernote-bs4.min.css" />
   {{-- DateTime Picker --}}
   <link rel="stylesheet" href="/assets/jquery-datetime-picker/dist/jquery-ui-timepicker-addon.min.css" />
   
 </head>
 
 <body class="">
   <div class="wrapper ">
     <div class="sidebar" data-color="new-orange" data-background-color="white" data-image="/assets/img/sidebar-1.jpg">
       <!--
         Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"
 
         Tip 2: you can also add an image using data-image tag
     -->
       <div class="logo">

        <div class="logo-container">
          <a class="company-logo" href="/vendor/dashboard">
            <img src="/assets/img/company-logo.png" alt="">
          </a>
        </div>
        <div class="profile-container">
            <img src="{{getProfilePhoto(Auth::user()->id)}}" alt="">
        </div>
        
         <a href="/vendor/dashboard" class="simple-text logo-normal">
          {{-- {{$reseller_data->reseller_name}} --}}
            @php
                if (Auth::guard('reseller')->check()){
                    $reseller = Auth::user()->profile;
                }
            @endphp
            {{isset($reseller->reseller_name)? $reseller->reseller_name: ""}}
            {{-- {{dd(auth('reseller'))}} --}}
          {{-- @php
              print_r(auth('reseller')->name);
              die;
          @endphp --}}
         </a>
       </div>
       <div class="sidebar-wrapper">
            <ul class="nav" id="sidebar_custom">
                <li class="nav-item" id="dashboard_item">
                    <a class="nav-link" href="/vendor/dashboard">
                        <i class="material-icons">dashboard</i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item" id="profile_item">
                    <a class="nav-link collapser" data-toggle="collapse" href="#profile_collapser" aria-expanded="false">
                        <i class="material-icons">portrait</i>
                        <p> Profile
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="profile_collapser">
                        <ul class="nav">
                            <li class="nav-item" id="reseller_updateprofile_subitem">
                                <a class="nav-link" href="/vendor/update/primary-information">
                                {{-- <span class="sidebar-mini"> P </span> --}}
                                <span class="sidebar-normal">Update Primary Information </span>
                                </a>
                            </li>
                            <li class="nav-item" id="reseller_aboutus_subitem">
                                <a class="nav-link" href="/vendor/about-us">
                                {{-- <span class="sidebar-mini"> P </span> --}}
                                <span class="sidebar-normal">About Us</span>
                                </a>
                            </li>
                            <li class="nav-item" id="reseller_shippingpolicy_subitem">
                                <a class="nav-link" href="/vendor/shipping-policy">
                                {{-- <span class="sidebar-mini"> P </span> --}}
                                <span class="sidebar-normal">Shipping Policy</span>
                                </a>
                            </li>
                            <li class="nav-item" id="reseller_returnpolicy_subitem">
                                <a class="nav-link" href="/vendor/return-policy">
                                <span class="sidebar-normal">Return Policy</span>
                                </a>
                            </li>
                            <li class="nav-item" id="reseller_paymentinfo_subitem">
                                <a class="nav-link" href="/vendor/payment-information">
                                <span class="sidebar-normal">Payment Information</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (Auth::guard('reseller')->check())
                    @php $reseller = Auth::user()->profile; @endphp
                    @switch($reseller->reseller_position)
                        {{-- PARENT RESELLER --}}
                        @case(0)
                            <li class="nav-item" id="child_management">
                                <a class="nav-link collapser" data-toggle="collapse" href="#child_management_collapser" aria-expanded="false">
                                    <i class="material-icons">supervisor_account</i>
                                    <p class="long-title"> Additional Users
                                        <b class="caret"></b>
                                    </p>
                                </a>
                                <div class="collapse" id="child_management_collapser">
                                    <ul class="nav">
                                        <li class="nav-item" id="child_management_childlist_subitem">
                                            <a class="nav-link" href="/vendor/secondary-vendor-management/secondary-list">
                                            {{-- <span class="sidebar-mini"> P </span> --}}
                                            <span class="sidebar-normal"> Additional Users </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            @break
                        {{-- /PARENT RESELLER --}}
                        {{-- CHILD RESELLER --}}
                        @case(1)
                            
                            @break
                        {{-- /CHILD RESELLER --}}
                        @default
                            
                    @endswitch
                @endif

                <li class="nav-item" id="items">
                    <a class="nav-link" href="/vendor/items">
                        <i class="material-icons">view_list</i>
                        <p>Items</p>
                    </a>
                </li>
                <li class="nav-item" id="shipment_rate">
                    <a class="nav-link" href="/vendor/shipment-rate">
                        <i class="material-icons">local_shipping</i>
                        <p>Shipment Rate</p>
                    </a>
                </li>
                <li class="nav-item" id="order">
                    <a class="nav-link" href="/vendor/order">
                        <i class="material-icons">list_alt</i>
                        <p>Order</p>
                    </a>
                </li>
            </ul>
       </div>
     </div>
     <div class="main-panel">
       <!-- Navbar -->
       <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
         <div class="container-fluid">
           <div class="navbar-wrapper">
             {{-- <a class="navbar-brand" href="#pablo">Dashboard</a> --}}
             @yield('breadcrumb')
           </div>
           <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
             <span class="sr-only">Toggle navigation</span>
             <span class="navbar-toggler-icon icon-bar"></span>
             <span class="navbar-toggler-icon icon-bar"></span>
             <span class="navbar-toggler-icon icon-bar"></span>
           </button>
           <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form"></form>
             <ul class="navbar-nav">
               {{-- <li class="nav-item dropdown">
                 <a class="nav-link" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="material-icons">notifications</i>
                   <span class="notification">5</span>
                   <p class="d-lg-none d-md-block">
                     Some Actions
                   </p>
                 </a>
                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                   <a class="dropdown-item" href="#">Mike John responded to your email</a>
                   <a class="dropdown-item" href="#">You have 5 new tasks</a>
                   <a class="dropdown-item" href="#">You're now friend with Andrew</a>
                   <a class="dropdown-item" href="#">Another Notification</a>
                   <a class="dropdown-item" href="#">Another One</a>
                 </div>
               </li> --}}
               <li class="nav-item dropdown">
                 <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="material-icons">person</i>
                   <p class="d-lg-none d-md-block">
                     Account
                   </p>
                 </a>
                 <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                   <a class="dropdown-item" href="#">Profile</a>
                   <a class="dropdown-item" href="#">Settings</a>
                   <div class="dropdown-divider"></div>
                   <a class="dropdown-item" href="/vendor/logout">Log out</a>
                 </div>
               </li>
             </ul>
           </div>
         </div>
       </nav>
       <!-- End Navbar -->
       {{-- START CONTENT --}}
       @yield('content')
       {{-- END CONTENT --}}
         
       
       <footer class="footer">
         <div class="container-fluid">
           <div class="copyright float-right">
             &copy;
             <script>
               document.write(new Date().getFullYear())
             </script>
             Betsy
           </div>
         </div>
       </footer>
     </div>
   </div>
   <!--   Core JS Files   -->
   <script src="/assets/js/core/jquery.min.js"></script>
   <script src="/assets/js/core/popper.min.js"></script>
   <script src="/assets/js/core/bootstrap-material-design.min.js"></script>
   <script src="/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
   <!-- Plugin for the momentJs  -->
   <script src="/assets/js/plugins/moment.min.js"></script>
   <!--  Plugin for Sweet Alert -->
   <script src="/assets/js/plugins/sweetalert2.js"></script>
   <!-- Forms Validations Plugin -->
   <script src="/assets/js/plugins/jquery.validate.min.js"></script>
   <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
   <script src="/assets/js/plugins/jquery.bootstrap-wizard.js"></script>
   <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
   <script src="/assets/js/plugins/bootstrap-selectpicker.js"></script>
   <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
   <script src="/assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
   <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
   <script src="/assets/js/plugins/jquery.dataTables.min.js"></script>
   <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
   <script src="/assets/js/plugins/bootstrap-tagsinput.js"></script>
   <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
   <script src="/assets/js/plugins/jasny-bootstrap.min.js"></script>
   <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
   <script src="/assets/js/plugins/fullcalendar.min.js"></script>
   <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
   <script src="/assets/js/plugins/jquery-jvectormap.js"></script>
   <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
   <script src="/assets/js/plugins/nouislider.min.js"></script>
   <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
   <!-- Library for adding dinamically elements -->
   <script src="/assets/js/plugins/arrive.min.js"></script>
   <!-- Chartist JS -->
   <script src="/assets/js/plugins/chartist.min.js"></script>
   <!--  Notifications Plugin    -->
   <script src="/assets/js/plugins/bootstrap-notify.js"></script>
   <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
   <script src="/assets/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>
   <!-- Material Dashboard DEMO methods, don't include it in your project! -->
   {{-- <script src="/assets/demo/demo.js"></script>
   <script type="text/javascript" src="/assets/js/material-dashboard.min.js"></script> --}}
   {{-- JQuery UI --}}
   <script type="text/javascript" src="/assets/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
   {{-- Datatable --}}
   <script type="text/javascript" src="/assets/datatable/datatables.min.js"></script>
   {{-- Modal alert --}}
   <script type="text/javascript" src="/assets/js/custom/custom-modal-alert.js"></script>

   {{-- Switchery --}}
   <script src="/assets/switchery-master/dist/switchery.js"></script>
   {{-- SummerNote --}}
   <script src="/assets/summernote-0.8.16/summernote-bs4.min.js"></script>
   {{-- DateTime Picker --}}
   <script src="/assets/jquery-datetime-picker/dist/jquery-ui-timepicker-addon.min.js"></script>
   <script src="/assets/jquery-datetime-picker/src/jquery-ui-sliderAccess.js"></script>

   <script>
     $(document).ready(function() {
       $().ready(function() {
         $sidebar = $('.sidebar');
        //  $sidebar.attr('data-color', "green");
         $sidebar_img_container = $sidebar.find('.sidebar-background');
 
         $full_page = $('.full-page');
        //  $full_page.attr('filter-color', "green");

         $sidebar_responsive = $('body > .navbar-collapse');
        //  $sidebar_responsive.attr('data-color', "green");

         window_width = $(window).width();
 
         fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();
 
         if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
           if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
             $('.fixed-plugin .dropdown').addClass('open');
           }
 
         }
 
         $('.fixed-plugin a').click(function(event) {
           // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
           if ($(this).hasClass('switch-trigger')) {
             if (event.stopPropagation) {
               event.stopPropagation();
             } else if (window.event) {
               window.event.cancelBubble = true;
             }
           }
         });
 
         $('.fixed-plugin .active-color span').click(function() {
           $full_page_background = $('.full-page-background');
 
           $(this).siblings().removeClass('active');
           $(this).addClass('active');
 
           var new_color = $(this).data('color');
 
           if ($sidebar.length != 0) {
             $sidebar.attr('data-color', new_color);
           }
 
           if ($full_page.length != 0) {
             $full_page.attr('filter-color', new_color);
           }
 
           if ($sidebar_responsive.length != 0) {
             $sidebar_responsive.attr('data-color', new_color);
           }
         });
 
         $('.fixed-plugin .background-color .badge').click(function() {
           $(this).siblings().removeClass('active');
           $(this).addClass('active');
 
           var new_color = $(this).data('background-color');
 
           if ($sidebar.length != 0) {
             $sidebar.attr('data-background-color', new_color);
           }
         });
 
         $('.fixed-plugin .img-holder').click(function() {
           $full_page_background = $('.full-page-background');
 
           $(this).parent('li').siblings().removeClass('active');
           $(this).parent('li').addClass('active');
 
 
           var new_image = $(this).find("img").attr('src');
 
           if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
             $sidebar_img_container.fadeOut('fast', function() {
               $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
               $sidebar_img_container.fadeIn('fast');
             });
           }
 
           if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
             var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
 
             $full_page_background.fadeOut('fast', function() {
               $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
               $full_page_background.fadeIn('fast');
             });
           }
 
           if ($('.switch-sidebar-image input:checked').length == 0) {
             var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
             var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');
 
             $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
             $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
           }
 
           if ($sidebar_responsive.length != 0) {
             $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
           }
         });
 
         $('.switch-sidebar-image input').change(function() {
           $full_page_background = $('.full-page-background');
 
           $input = $(this);
 
           if ($input.is(':checked')) {
             if ($sidebar_img_container.length != 0) {
               $sidebar_img_container.fadeIn('fast');
               $sidebar.attr('data-image', '#');
             }
 
             if ($full_page_background.length != 0) {
               $full_page_background.fadeIn('fast');
               $full_page.attr('data-image', '#');
             }
 
             background_image = true;
           } else {
             if ($sidebar_img_container.length != 0) {
               $sidebar.removeAttr('data-image');
               $sidebar_img_container.fadeOut('fast');
             }
 
             if ($full_page_background.length != 0) {
               $full_page.removeAttr('data-image', '#');
               $full_page_background.fadeOut('fast');
             }
 
             background_image = false;
           }
         });
 
         $('.switch-sidebar-mini input').change(function() {
           $body = $('body');
 
           $input = $(this);
 
           if (md.misc.sidebar_mini_active == true) {
             $('body').removeClass('sidebar-mini');
             md.misc.sidebar_mini_active = false;
 
             $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
 
           } else {
 
             $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');
 
             setTimeout(function() {
               $('body').addClass('sidebar-mini');
 
               md.misc.sidebar_mini_active = true;
             }, 300);
           }
 
           // we simulate the window Resize so the charts will get updated in realtime.
           var simulateWindowResize = setInterval(function() {
             window.dispatchEvent(new Event('resize'));
           }, 180);
 
           // we stop the simulation of Window Resize after the animations are completed
           setTimeout(function() {
             clearInterval(simulateWindowResize);
           }, 1000);
 
         });
       });
     });
   </script>
   <script>
     $(document).ready(function() {
       // Javascript method's body can be found in assets/js/demos.js
       md.initDashboardPageCharts();
 
     });
   </script>
    <script>
        $('#sidebar_custom').find('.nav-item').removeClass('active');
    </script>
    @yield('script')
 </body>
 
 </html>
 