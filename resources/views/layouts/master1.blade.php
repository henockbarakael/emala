<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portefeuille numérique à la pointe de la technologie, Emala vous permet de faire des transactions financières sécurisées.">
    <meta name="keywords" content="EMALA, Emala, emala, emalafintech, fintech">
    <meta property="og:image" content="http://dashboard.emalafintech.net/assets/img/logo.png" />
    <meta property="og:image:secure_url" content="https://dashboard.emalafintech.net/assets/img/logo.png" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Emala Fintech" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Henock BARAKAEL | barahenock@gmail.com | +243828584688">
    <link rel="icon" href="{{ asset('assets/images/icon1.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/icon1.png')}}" type="image/x-icon">
    <title>@yield('title')</title>
    <!-- Google font-->
    <link href="{{ asset('dist/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-1?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.css')}}">
    <!-- ico-font-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/icofont.css')}}">
    <!-- Themify icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/themify.css')}}">
    <!-- Flag icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/flag-icon.css')}}">
    <!-- Feather icon-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/feather-icon.css')}}">
    <!-- Plugins css start-->
    <link
      href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet"/>
    <style>.myshadow{
      box-shadow: 0 10px 5px -1px rgba(0,0,0,.2),0 15px 18px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12)!important;
      border-radius: 8px;
    }</style>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/prism.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/dropzone.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/daterange-picker.css')}}">
    <script src="{{ asset('assets/js/contacts/custom.js')}}"></script>
    <!-- Plugins css Ends-->
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/bootstrap.css')}}">
    <!-- App css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/color-1.css')}}" media="screen">
    <!-- Responsive css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}">
		<script src="{{ asset('assets/js/toastr_jquery.min.js') }}"></script>
		<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
  </head>
  <body class="dark-only">
    {{-- <body class="dark-sidebar"> --}}
    <!-- loader starts-->
    @include('layouts.loader')
    <!-- loader ends-->
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      <!-- Page Header Start-->
      @include('layouts.header')
      <div class="page-body-wrapper">
        <!-- Page Sidebar Start-->
        @include('layouts.sidebar')
        <!-- Page Sidebar Ends-->
        @yield('content')
        <!-- footer start-->
        @include('layouts.footer')
      </div>
    </div>
    
    <div class="modal fade" id="cashregister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
              <div class="text-center mt-4">
                  <h5>Session de caisse <i class="fa fa-warning text-warning"></i></h5>
                  <p class="message" style="font-size: 12px"></p>
              </div>
          </div>
          <div class="modal-btn">
              <form action="" method="POST">
                  @csrf
                  <input type="hidden" name="id" class="e_id" value="">
                  <div class="row">
                      <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                          <button class="btn btn-primary" type="button" id="btn_continious" onclick="continious()">Continuer</button>
                          <button class="btn btn-secondary" type="button" id="btn_closing" onclick="closing()">Clôturer</button>
                        </div>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </div>

    <!-- latest jquery-->
    {{-- <script src="{{ asset('assets/js/jquery-3.5.1.min.js')}}"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"> </script>
    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
    <!-- scrollbar js-->
    <script src="{{ asset('assets/js/scrollbar/simplebar.js')}}"></script>
    <script src="{{ asset('assets/js/scrollbar/custom.js')}}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('assets/js/config.js')}}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone.js')}}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ asset('assets/js/chart/google/google-chart-loader.js')}}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob.min.js')}}"></script>
    <script src="{{ asset('assets/js/chart/knob/knob-chart.js')}}"></script>
    <script src="{{ asset('assets/js/chart/google/google-chart.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/vfs_fonts.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/js/datatable/datatable-extension/jszip.min.js')}}"></script>
    <script src="{{ asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js')}}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard_2.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/moment.min.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/daterangepicker.js')}}"></script>
    <script src="{{ asset('assets/js/datepicker/daterange-picker/daterange-picker.custom.js')}}"></script>
    
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('assets/js/script.js')}}"></script>

    

    <script>
      function ajaxReq() {
        var url = '';
        var redirect = '';
    
        var role = "{{ Auth::user()->role_name }}";
    
        switch (role) {
  case 'Admin':
    url = '{{ route("admin.cloture_caisse") }}';
    redirect = '{{ route("admin.dashboard") }}'; // Redirection vers la page d'administration après la clôture
    break;
  case 'Manager':
    url = '{{ route("manager.cloture_caisse") }}';
    redirect = '{{ route("manager.dashboard") }}'; // Redirection vers la page de gestionnaire après la clôture
    break;
  case 'Cashier':
    url = '{{ route("cashier.cloture_caisse") }}';
    redirect = '{{ route("cashier.dashboard") }}'; // Redirection vers la page de caissier après la clôture
    break;
  default:
    // Cas par défaut si aucun rôle ne correspond
    break;
}
    
        var csrf_token = '{{ csrf_token() }}';
    
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
    
        $.ajax({
          type: 'GET',
          url: url,
          processData: false,
          contentType: false,
          success: function(response) {
            if (response.success == false) {
              $("#cashregister").modal("show");
              $('.message').html(response.message).fadeIn('slow');
            } else {
              window.location.href = redirect;
            }
          }
        });
      }
    
      $('#btn_ajax').on('click', ajaxReq);
    </script>
    <script>
      function closing() {
        var url = '';
        var redirect = '';

        var role = "{{ Auth::user()->role_name }}";

        switch (role) {
          case 'Admin':
            url = '{{ route("admin.cloture_caisse") }}';
            break;
          case 'Manager':
            url = '{{ route("manager.cloture_caisse") }}';
            break;
          case 'Cashier':
            url = '{{ route("cashier.cloture_caisse") }}';
            break;
          default:
            // Cas par défaut si aucun rôle ne correspond
            break;
        }

        var csrf_token = '{{ csrf_token() }}';

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: 'GET',
          url: url,
          processData: false,
          contentType: false,
          success: function(response) {
            window.location.href = redirect; // Rediriger vers la page spécifiée après la clôture
          }
        });
      }

      $('#btn_ajax').on('click', closing);
    </script>

    @yield('script')
  </body>
</html>
