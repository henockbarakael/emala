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
    <title>Wallet Agence</title>
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
@section('page','Demande de recharge')
@section('page_1','Agence')
@section('page_2','Demande de recharge')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('sweetalert::alert')
    @include('layouts.page-title')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <!-- Flexible table width Starts-->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
              <div class="table-responsive">
                <table class="display" id="basic-8">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Agence</th>
                        <th>Montant</th>
                        <th>Devise</th>
                        <th>Wallet</th>
                        <th>Status</th>
                        <th class="text-center">Recharge</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($requests as $key => $value)
                      <tr>
                        <td class="id">{{$value->id}}</td>
                        <td class="bname">{{$value->bname}}</td>
                        <td class="amount">{{$value->amount}}</td>
                        <td class="currency">{{$value->currency}}</td>
                        <td class="w_code">{{$value->wallet_code}}</td>
                        <td class="status">{{$value->status}}</td>
                        <td class="text-center">
                            <a href="" class="btn btn-success btn-xs recharge" title="Recharger wallet" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-plus"></i></a>
                          </td>
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

      <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Recharger compte agence</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.recharge.process')}}" method="POST">
                    @csrf
                    <input type="hidden" name="w_id" id="e_w_id" value="">
                    <div class="row g-3">
                        
                        <div class="col-md-4">
                            <label class="form-label" for="w_code">N° compte</label>
                            <input readonly class="form-control @error('w_code') is-invalid @enderror" name="w_code" id="e_w_code" type="text" value="" >
                            @error('w_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="amount">Montant</label>
                            <input readonly class="form-control @error('amount') is-invalid @enderror" name="amount" id="e_amount" type="text" value="" >
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="currency">Devise</label>
                            <input readonly class="form-control @error('currency') is-invalid @enderror" name="currency" id="e_currency" type="text" value="" >
                            @error('currency')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>

                    </div>
                    
                    <div class="modal-footer mt-5">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Recharger</button>
                      </div>
                  </form>
              </div>
              
          </div>
        </div>
      </div>
    
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

    <script>
        $(document).on('click','.recharge',function()
        {
            var _this = $(this).parents('tr');
            $('#e_w_id').val(_this.find('.id').text());
            $('#e_w_code').val(_this.find('.w_code').text());
            $('#e_amount').val(_this.find('.amount').text());
            $('#e_currency').val(_this.find('.currency').text());
        });
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_ids').val(_this.find('.id').text());
        });
    </script>
    @endsection
@endsection