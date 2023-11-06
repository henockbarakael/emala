@extends('layouts.master')
@push('style')
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portefeuille numérique à la pointe de la technologie, Emala vous permet de faire des transactions financières sécurisées.">
    <meta name="keywords" content="EMALA, Emala, emala, emalafintech, fintech">
    <meta property="og:image" content="http://dashboard.emalafintech.net/backend/img/logo.png" />
    <meta property="og:image:secure_url" content="https://dashboard.emalafintech.net/backend/img/logo.png" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Emala Fintech" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Henock BARAKAEL | barahenock@gmail.com | +243828584688">
    <link rel="icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <title>@yield('title')</title>
    <link href="{{ asset('dist/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-1?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/icofont.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/themify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/flag-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/feather-icon.css')}}">
    <link href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet"/>
    <script src="{{ asset('backend/js/contacts/custom.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('backend/css/color-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/css/toastr.min.css') }}">
	<script src="{{ asset('backend/js/toastr_jquery.min.js') }}"></script>
	<script src="{{ asset('backend/js/toastr.min.js') }}"></script>
    <style>
        .myshadow{
            box-shadow: 0 10px 5px -1px rgba(0,0,0,.2),0 15px 18px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12)!important;
            border-radius: 8px;
        }
    </style>
</head>
@endpush
@section('content')
@section('title','Bienvenue | Emala FinTech')
@section('page','Dashboard')
{!! Toastr::message() !!}
<div class="page-body">
    {{-- @include('layouts.page-title') --}}
    @include('sweetalert::alert')
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-6">
            <h3>Dashboard</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 col-xl-12">
          <div class="card">
            <div class="card-body row">

              <div class="col-xl-6 col-sm-6 box-col-6 ">
                <div class="myshadow p-25 text-center text-white" style="background-color: #119e3e;">
                  <span>SOLDE CAISSE CDF</span>
                  <h5 class="m-0 f-18">{{ $cashierCDF }}</h5>
                </div>
              </div>


              <div class="col-xl-6 col-sm-6 box-col-6">
                <div class="myshadow p-25 text-center text-white" style="background-color: #119e3e;">
                  <span>SOLDE CAISSE USD</span>
                  <h5 class="m-0 f-18">{{ $cashierUSD }}</h5>
                </div>
              </div>
              

              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #DB1430;">
                  <span>Total Transaction</span>
                  <h5 class="m-0 f-18">{{ $transactions->total }}</h5>
                </div>
              </div>

              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #DB1430;">
                  <span>Total Dépôt</span>
                  <h5 class="m-0 f-18">{{ $transactions->depot }}</h5>
                </div>
              </div>

              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #DB1430;">
                  <span>Total Retrait</span>
                  <h5 class="m-0 f-18">{{ $transactions->retrait }}</h5>
                </div>
              </div>

              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #DB1430;">
                  <span>Total Client</span>
                  <h5 class="m-0 f-18">{{$transactions->transfert}}</h5>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="col-sm-12">
          <div class="card">
            {{-- <div class="card-header">
              <h5>Custom Table Color with Hover and Stripped</h5><span>Use class<code>table-hover, table-striped table-*</code><code>table-info</code>,<code>table-success</code>,<code>table-success</code>,<code>table-info</code>,<code>table-danger</code>,<code>table-primary</code>,<code>table-secondary</code>,<code>table-light</code>,<code>table-active</code> inside table element.</span>
            </div> --}}
            <div class="card-block row">
              <div class="col-sm-12 col-lg-12 col-xl-12">
                <div class="table-responsive table-border">
                  <table class="table table-styling table-hover table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Catégorie</th>
                        <th scope="col">Montant</th>
                        <th scope="col">Devise</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                          $counter = 1;
                      @endphp
                      <tr>
                        <th scope="row">{{ $counter }}</th>
                        <td>Retrait</td>
                        <td>{{ $retrait_cdf }}</td>
                        <td>CDF</td>
                      </tr>
                      @php
                          $counter++;
                      @endphp
                      <tr>
                        <th scope="row">{{ $counter }}</th>
                        <td>Retrait</td>
                        <td>{{ $retrait_usd }}</td>
                        <td>USD</td>
                      </tr>
                      @php
                          $counter++;
                      @endphp
                      <tr>
                        <th scope="row">{{ $counter }}</th>
                        <td>Dépôt</td>
                        <td>{{ $depot_cdf }}</td>
                        <td>CDF</td>
                      </tr>
                      @php
                          $counter++;
                      @endphp
                      <tr>
                        <th scope="row">{{ $counter }}</th>
                        <td>Dépôt</td>
                        <td>{{ $depot_usd }}</td>
                        <td>USD</td>
                      </tr>
                      @php
                          $counter++;
                      @endphp
                      <tr>
                        <th scope="row">{{ $counter }}</th>
                        <td>Transfert</td>
                        <td>{{ $transfert_cdf }}</td>
                        <td>CDF</td>
                      </tr>
                      @php
                          $counter++;
                      @endphp
                      <tr>
                        <th scope="row">{{ $counter }}</th>
                        <td>Transfert</td>
                        <td>{{ $transfert_usd }}</td>
                        <td>USD</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      
  </div>
  </div>
  @section('script')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"> </script>
    <script src="{{ asset('backend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('backend/js/icons/feather-icon/feather.min.js')}}"></script>
    <script src="{{ asset('backend/js/icons/feather-icon/feather-icon.js')}}"></script>
    <script src="{{ asset('backend/js/scrollbar/simplebar.js')}}"></script>
    <script src="{{ asset('backend/js/scrollbar/custom.js')}}"></script>
    <script src="{{ asset('backend/js/config.js')}}"></script>
    <script src="{{ asset('backend/js/sidebar-menu.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script src="{{ asset('backend/js/chart/google/google-chart-loader.js')}}"></script>
    <script src="{{ asset('backend/js/chart/knob/knob.min.js')}}"></script>
    <script src="{{ asset('backend/js/chart/knob/knob-chart.js')}}"></script>
    <script src="{{ asset('backend/js/chart/google/google-chart.js')}}"></script>
    <script src="{{ asset('backend/js/sweet-alert/sweetalert.min.js')}}"></script>
    <script src="{{ asset('backend/js/tooltip-init.js')}}"></script>
    <script src="{{ asset('backend/js/script.js')}}"></script>
    <script src="{{ asset('backend/js/chart/apex-chart/apex-chart.js')}}"></script>
    <script src="{{ asset('backend/js/chart/apex-chart/chart-custom.js')}}"></script>
  @endsection
@endsection