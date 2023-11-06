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
    <style>
    .myshadow{
      box-shadow: 0 10px 5px -1px rgba(0,0,0,.2),0 15px 18px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12)!important;
      border-radius: 8px;
    }
    .equal-height {
        display: flex;
        flex-wrap: wrap;
    }

    .equal-height > [class^="col-"] {
        display: flex;
        flex-direction: column;
    }
    </style>
    
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
@section('page','Détails du compte')
@section('page_1','Client')
@section('page_2','Détails du compte')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('sweetalert::alert')
    @include('layouts.page-title')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="edit-profile">
        <div class="row">
          <div class="col-xl-4">
            <div class="card">
              <div class="card-header bg-success">
                {{-- <h5 class="card-title mb-0" style="font-size: 16px; font-weight:bold">Agence: {{$data['bname']}}</h5> --}}
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
              </div>
              <div class="card-body">
                
                  <div class="row mb-2">
                    <div class="profile-title">
                      <div class="media">
                      {{-- <img class="img-70 rounded-circle" alt="" src="{{ URL::to('/assets/images/user/'. $data['avatar']) }}"> --}}
                      <img class="img-100" alt="" src="https://frontend.emalafintech.net/assets/img/{{ $data['avatar'] }}">
                        <div class="media-body">
                          <h5 class="mb-1">{{$data['firstname']." ".$data['lastname']}}</h5>
                          <p>@if ($data['role_name']== 'Cashier')
                              Caissier
                              @elseif($data['role_name'] == 'Manager')
                              Gérant
                          @endif</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <h6 class="form-label">Téléphone</h6>
                    <p>{{$data['phone_number']}}</p>
                  </div>
                  <div class="mb-3">
                      <h6 class="form-label">E-mail</h6>
                      <p>{{$data['email']}}</p>
                  </div>
                  <div class="mb-3">
                      <h6 class="form-label">Adresse</h6>
                      <p>{{$data['city'].', '.$data['country']}}</p>
                  </div>
                  <div class="mb-3">
                      <h6 class="form-label">Date d'adhésion</h6>
                      <p>{{$data['join_date']}}</p>
                  </div>
              </div>
            </div>
          </div>
          <div class="col-xl-8">
            <div class="col-md-12">
              <form class="card">
                <div class="card-body">
                  <div class="row">
                      <a class="col-sm-5 col-md-5 mb-3 btn btn-success" href="{{ url('admin/transfert/interne/'.Crypt::encrypt($data['id_user']))}}"  id="addDefault">Transfert</a>
                      <div class="col-sm-2 col-md-2"></div>
                      <a class="col-sm-5 col-md-5 mb-3 btn btn-success" href="{{ url('admin/retrait/cash/'.Crypt::encrypt($data['id_user']))}}"  id="addDefault">Retrait</a>
              
                      <a class="col-sm-5 col-md-5 mb-3 btn btn-success" href="{{ url('admin/depot/cash/'.Crypt::encrypt($data['id_user']))}}"  id="addDefault">Dépôt</a>
                      <div class="col-sm-2 col-md-2"></div>
              
                      @if(isset($data['pret']) && ($data['pret']['status'] == 'En attente' || $data['pret']['status'] == 'Approuvé'))
                          <div class="col-sm-5 col-md-5 mb-3 btn btn-success disabled" id="addDefault">Demande de Prêt</div>
                          @if ($data['pret']['status'] == 'En attente')
                              <p class="col-sm-12 col-md-12 mt-2 text-info">
                                  Le client a une demande de prêt en attente. Veuillez la traiter dès que possible en cliquant 
                                  <a href="{{ route('admin.loans.history', ['id' => $data['pret']['id']]) }}">
                                      <i class="icon-hand-point-right" style="color: #D2B48C;"></i> ici
                                  </a>. 
                              </p>
                          @elseif ($data['pret']['status'] == 'Approuvé')
                              <a class="col-sm-5 col-md-5 btn btn-primary" href="{{ route('admin.loans.show', ['id' => $data['pret']['id']]) }}" id="addDefault">Amortissement prêt</a>
                          @endif
                      @else
                          <a class="col-sm-5 col-md-5 mb-3 btn btn-success" href="{{ route('admin.loans.createId', ['id' => $data['id_user']]) }}"  id="addDefault">Demande de Prêt</a>
                      @endif
              
                      @if(isset($data['pret']) && $data['pret']['status'] == 'Approuvé')
                          <div class="col-sm-2 col-md-2"></div>
                          <a class="col-sm-5 col-md-5 btn btn-primary" href="{{ route('admin.payments.create', ['loanId' => $data['pret']['id']]) }}" id="addDefault">Paiement prêt</a>
                      @endif
                  </div>
              </div>
              </form>
            </div>
            <div class="col-md-12">
              <form class="card">
                <div class="card-header">
                  <h4 class="card-title mb-0">Solde compte</h4>
                  <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
                <div class="card-body">
                  <div class="row">
                    @if(isset($data['pret']))
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#fff; border-color:#fff; color:#1C1E21">Principal {{$data['cnumber']}}</button>
                    <div class="col-sm-1 col-md-1"></div>
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#fff; border-color:#fff; color:#1C1E11">Epargne {{$data['snumber']}}</button>
                    <div class="col-sm-1 col-md-1"></div>
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#fff; border-color:#fff; color:#1C1E21">Prêt</button>

                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['c_bcdf']}} CDF</button>
                    <div class="col-sm-1 col-md-1"></div>
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['s_bcdf']}} CDF</button>
                    <div class="col-sm-1 col-md-1"></div>
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">Total: {{$data['pret']['total_amount_due'].' '.$data['pret']['currency']}}</button>


                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['c_busd']}} USD</button>
                    <div class="col-sm-1 col-md-1"></div>
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['s_busd']}} USD</button>
                    {{-- <div class="col-sm-1 col-md-1"></div> --}}

                    {{-- <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['pret']['total_amount_due'].' '.$data['pret']['currency']}}</button> --}}
                    <div class="col-sm-1 col-md-1"></div>
                    <button disabled class="col-sm-3 col-md-3 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">Reste: {{$data['pret']['balance'].' '.$data['pret']['currency']}}</button>
                    @else
                    <button disabled class="col-sm-5 col-md-5 mb-3" style="background-color:#fff; border-color:#fff; color:#1C1E21">Compte Principal {{$data['cnumber']}}</button>
                    <div class="col-sm-2 col-md-2"></div>
                    <button disabled class="col-sm-5 col-md-5 mb-3" style="background-color:#fff; border-color:#fff; color:#1C1E21">Compte Epargne {{$data['snumber']}}</button>

                    <button disabled class="col-sm-5 col-md-5 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['c_bcdf']}} CDF</button>
                    <div class="col-sm-2 col-md-2"></div>
                    <button disabled class="col-sm-5 col-md-5 mb-3" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['s_bcdf']}} CDF</button>

                    <button disabled class="col-sm-5 col-md-5" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['c_busd']}} USD</button>
                    <div class="col-sm-2 col-md-2"></div>
                    <button disabled class="col-sm-5 col-md-5" style="background-color:#1C1E21; border-color:#1C1E21; color:white">{{$data['s_busd']}} USD</button>
                    @endif
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="col-sm-12">
            <div class="card">
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
                      @foreach ($data['transactions'] as  $key => $value)
                        <tr>
                          <td hidden>{{$value->id}}</td>
                          <td class="text-left"><a href="{{ url('admin/compte-client-phone/'.Crypt::encrypt($value->sender_phone)) }}">{{$value->sender_phone}}</a></td>
                          <td>{{$value->amount}}</td>
                          <td>{{$value->currency}}</td>
                          <td><a href="{{ url('admin/compte-client-phone/'.Crypt::encrypt($value->receiver_phone)) }}">{{$value->receiver_phone}}</a></td>
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
        </div>
      </div>
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

    <script>
        $(document).on('click','.recharge',function()
        {
            var _this = $(this).parents('tr');
            $('#e_w_code').val(_this.find('.w_code').text());
            $('#e_w_usd').val(_this.find('.w_usd').text());
            $('#e_w_cdf').val(_this.find('.w_cdf').text());
        });
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_ids').val(_this.find('.id').text());
        });
    </script>
    @endsection
@endsection