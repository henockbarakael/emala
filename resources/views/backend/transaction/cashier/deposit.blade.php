@extends('layouts.master')
@push('style')
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
    <link rel="icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <title>Transaction - Dépôt</title>
    <link href="{{ asset('dist/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-1?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/icofont.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/themify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/flag-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/feather-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link
      href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet"/>
    <style>.myshadow{
      box-shadow: 0 10px 5px -1px rgba(0,0,0,.2),0 15px 18px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12)!important;
      border-radius: 8px;
    }</style>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/daterange-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('backend/css/color-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/css/toastr.min.css') }}">
    <script src="{{ asset('backend/js/toastr_jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/toastr.min.js') }}"></script>
</head>
@endpush
@section('content')

{!! Toastr::message() !!}
  <div class="page-body">

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
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          {{-- <div class="card-header">
            <form class="row date-range-picker" action="{{route('cashier.transaction.search')}}" method="POST">
              @csrf
              <div class="col-xl-3">
                <div class="theme-form">
                    <input class="datepicker-here form-control digits input-air-primary" type="text" placeholder="Choisir une date" name="transaction_date" data-language="en">
                </div>
              </div>
              <div class="col-xl-3">
                <div class="theme-form">
                      <input class="form-control input-air-primary" type="text" name="sender_phone" placeholder="Numéro expéditeur">
                    </div>
              </div>
              <div class="col-xl-3">
                <div class="theme-form">
                    <input class="form-control input-air-primary" type="text" name="receiver_phone" placeholder="Numéro destinataire">
                </div>
              </div>
              <div class="col-xl-3">
                <div class="theme-form">
                    <button type="submit" class="btn btn-success btn-lg pull-right text-small" style="font-size: 14px"><span><i class="fa fa-search" style="font-size: 12px"></i></span> Rechercher </button>
                  </div>
              </div>
            </form>
          </div> --}}
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th hidden>Id</th>
                    <th class="text-left">Expéditeur</th>
                    <th class="text-left">Montant</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Bénéficiaire</th>
                    <th class="text-left">Frais</th>
                    {{-- <th class="text-left">Balance</th> --}}
                    <th class="text-left">Référence</th>
                    <th class="text-left">Type</th>
                    <th class="text-left">Status</th>
                    <th class="text-left">Date</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($transactions as  $key => $value)
                    <tr>
                      <td hidden>{{$value->id}}</td>
                      <td class="text-left"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->sender_phone)) }}">{{$value->sender_phone}}</a></td>
                      <td>{{$value->amount}}</td>
                      <td>{{$value->currency}}</td>
                      <td><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->receiver_phone)) }}">{{$value->receiver_phone}}</a></td>
                      <td>{{$value->fees}}</td>
                      {{-- <td>{{$value->current_balance}}</td> --}}
                      <td>{{$value->reference}}</td>
                      <td><span style="text-transform: capitalize">{{$value->category}}</span></td>
                      <td>{{$value->status}}</td> 
                      <td style="min-width:150px">{{$value->created_at}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Flexible table width  Ends-->
    </div>
    <!-- Container-fluid Ends-->
  </div>

@section('script')
<script src="{{ asset('backend/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ asset('backend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('backend/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{ asset('backend/js/icons/feather-icon/feather-icon.js')}}"></script>
<script src="{{ asset('backend/js/scrollbar/simplebar.js')}}"></script>
<script src="{{ asset('backend/js/scrollbar/custom.js')}}"></script>
<script src="{{ asset('backend/js/config.js')}}"></script>
<script src="{{ asset('backend/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('backend/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('backend/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ asset('backend/js/script.js')}}"></script>
    @endsection
@endsection