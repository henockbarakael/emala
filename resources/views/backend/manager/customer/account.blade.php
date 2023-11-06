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
@section('page','Compte')
@section('page_1','Compte')
@section('page_2','Agence')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('sweetalert::alert')
    @include('layouts.page-title')
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="user-profile">
        <div class="row">
          <div class="col-sm-3 chart_data_right box-col-3">
            <div class="card">
              <div class="card-body" style="padding: 10px">
                <div class="media align-items-center">
                  <div class="media-body right-chart-content">
                    <a style="width: 100%" href="{{ url('manager/transfert/interne/'.Crypt::encrypt($id_user))}}" class="btn btn-success" id="addDefault">Transfert</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3 chart_data_right box-col-3">
            <div class="card">
              <div class="card-body" style="padding: 10px">
                <div class="media align-items-center">
                  <div class="media-body right-chart-content">
                    <a style="width: 100%" href="{{ url('manager/retrait/cash/'.Crypt::encrypt($id_user)) }}" class="btn btn-success" id="addToDo">Retrait</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3 chart_data_right box-col-3">
            <div class="card">
              <div class="card-body" style="padding: 10px">
                <div class="media align-items-center">
                  <div class="media-body right-chart-content">
                    <a style="width: 100%" href="{{ url('manager/depot/cash/'.Crypt::encrypt($id_user)) }}" class="btn btn-success" id="removeBoard">Dépôt</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-3 chart_data_right box-col-3">
            <div class="card">
              <div class="card-body" style="padding: 10px">
                <div class="media align-items-center">
                  <div class="media-body right-chart-content">
                    <a style="width: 100%" href="{{ url('manager/pret-bancaire/interne/'.Crypt::encrypt($id_user)) }}" class="btn btn-success" id="removeBoard">Demande De Prêt</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if ($pret)
          <div class="col-sm-4 chart_data_right box-col-4">
            <div class="card">
                <div class="card-body" style="padding: 10px">
                    <div class="media align-items-center">
                        <div class="media-body right-chart-content">
                            
                                <a style="width: 100%" href="{{ url('manager/remboursement-pret/'.Crypt::encrypt($id_user)) }}" class="btn btn-success" id="removeBoard">Remboursement Prêt</a>
                            
                        </div>
                    </div>
                </div>
            </div>
          </div>
          @endif



          <div class="col-sm-12">
            <div class="card hovercard text-center">
              <div class="cardheader"></div>
              <div class="user-image">
                <div class="avatar"><img alt="" src="https://frontend.emalafintech.net/assets/profil/{{$avatar}}" width="86px" height="86px"></div>
                {{-- <div class="icon-wrapper"><i class="icofont icofont-pencil-alt-5"></i></div> --}}
              </div>
              <div class="info">
                <div class="row">

                  <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
                    <div class="user-designation">
                      <div class="title"><a target="_blank" href="">{{$firstname." ".$lastname}}</a></div>
                      <div class="desc">{{$role_name}}</div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="ttl-info text-start">
                          <h6><i class="fa fa-phone"></i>   Téléphone</h6><span>{{$phone_number}}</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="ttl-info text-start">
                          <h6><i class="fa fa-location-arrow"></i>   Adresse</h6><span>{{$address}}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="ttl-info text-start">
                          <h6><i class="fa fa-envelope"></i>   Ville</h6><span>{{$city}}</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="ttl-info text-start">
                          <h6><i class="fa fa-calendar"></i>   BOD</h6><span></span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="follow">
                  <div class="row">
                    <div class="col-6 text-md-end border-right">
                      <div class="follow-num counter">{{$cnumber}}</div><span>N° compte principal</span>
                      <h6>USD {{$c_busd}}</h6>
                      <h6>CDF {{$c_bcdf}}</h6>
                    </div>
                    <div class="col-6 text-md-start">
                      <div class="follow-num counter">{{$snumber}}</div><span>N° compte epargne</span>
                      <h6>USD {{$s_busd}}</h6>
                      <h6>CDF {{$s_bcdf}}</h6>
                    </div>
                  </div>
                </div>
              </div>
              
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
                        <th class="text-left">Balance</th>
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
                          <td class="text-left"><a href="{{ url('manager/compte-client-phone/'.Crypt::encrypt($value->transaction_from)) }}">{{$value->transaction_from}}</a></td>
                          <td>{{$value->amount}}</td>
                          <td>{{$value->currency}}</td>
                          <td><a href="{{ url('manager/compte-client-phone/'.Crypt::encrypt($value->transaction_to)) }}">{{$value->transaction_to}}</a></td>
                          <td>{{$value->fees}}</td>
                          <td>{{$value->current_balance}}</td>
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