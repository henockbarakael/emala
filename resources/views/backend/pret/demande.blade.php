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
    <title>Emala - Historique de prêt</title>
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
@section('page','Historique de prêt')
@section('page_1','Prêt')
@section('page_2','Historique de prêt')
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
                      {{-- <th>Client</th> --}}
                      <th hidden>ID</th>
                      <th>N° Dossier</th>
                      <th style="min-width: 150px">Montant du prêt</th>
                      <th style="min-width: 180px">Montant à rembourser</th>
                      <th style="min-width: 180px">Montant/Echéance</th>
                      <th style="min-width: 180px">Echéance</th>
                      <th>Devise</th>
                      <th>Durée</th>
                      {{-- <th>Type</th> --}}
                      <th style="min-width: 150px">Status</th>
                      {{-- <th style="min-width:300px">Objet</th> --}}
                      <th class="text-right">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($prets as $key => $value)
                    <tr>
                      {{-- <td class="phone_number">{{$value->phone_number}}</td> --}}
                      <td class="control_number">{{$value->control_number}}</td>
                      <td hidden class="id">{{$value->id}}</td>
                      <td class="loan_amount">{{$value->loan_amount}}</td>
                      <td class="principal_paid">{{$value->principal_paid}}</td>
                      <td class="amount_by_echeance">{{$value->amount_by_echeance}}</td>
                      <td class="echeance">{{$value->echeance}}</td>
                      <td class="loan_currency">{{$value->loan_currency}}</td>
                      <td class="loan_duration">{{$value->loan_duration}}mois</td>
                      {{-- <td class="loan_type">{{$value->loan_type}}</td> --}}
                      <td class="loan_status">{{$value->loan_status}}</td>
                      {{-- <td class="objet">{{$value->objet}}</td> --}}
                      <td>
                        @if ($value->loan_status == "En attente")
                        <a href="" class="btn btn-success btn-xs pretApprouvé"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#Approuvé">Approuvé</a>
                        <a href="" class="btn btn-danger btn-xs pretDesApprouvé"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#desApprouvé">DésApprouvé</a>
                        @else
                        <a href="#">-</a>
                        @endif
                        
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


      <div class="modal fade" id="desApprouvé" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-4">
                    <h3>DésApprouvé la demande</h3>
                    <p>Etes-vous sûre de désApprouvé la demande?</p>
                </div>
            </div>
            <div class="modal-btn">
                <form action="{{route('admin.demande.failed')}}" method="POST">
                    @csrf
                    <input type="hidden" name="control_number" class="d_control_number" value="">
                    <div class="row">
                        <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                            <button class="btn btn-secondary" type="submit">DésApprouvé</button>
                          </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>

      <div class="modal fade" id="Approuvé" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-body">
                <div class="text-center mt-4">
                    <h3>Approuvé la demande</h3>
                    <p>Etes-vous sûre d'Approuvé la demande?</p>
                </div>
            </div>
            <div class="modal-btn">
                <form action="{{route('admin.demande.success')}}" method="POST">
                    @csrf
                    <input type="hidden" name="control_number" class="a_control_number" value="">
                    <div class="row">
                        <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                            <button class="btn btn-secondary" type="submit">Approuvé</button>
                          </div>
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
        $(document).on('click','.pretDesApprouvé',function()
        {
            var _this = $(this).parents('tr');
            $('.d_control_number').val(_this.find('.control_number').text());
        });
        $(document).on('click','.pretApprouvé',function()
        {
            var _this = $(this).parents('tr');
            $('.a_control_number').val(_this.find('.control_number').text());
        });
    </script>
    @endsection
@endsection