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
    <title>Transaction - Transfert d'argent</title>
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
@section('page','Transfert')
@section('page_1','Transaction')
@section('page_2','Transfert compte à ompte')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('sweetalert::alert')
    @include('layouts.page-title')

    <!-- Container-fluid starts-->
    <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="invoice">
                  <div>
                    <div>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="media">
                            <div class="media-body m-l-20 text-right">
                              <h4 class="media-heading">Transfert compte à ompte</h4>
                              <p>Transférer de l'argent de votre compte vers un autre devient plus facile avec Emala.<br><span></span></p>
                            </div>
                          </div>
                          <!-- End Info-->
                        </div>
                      </div>
                    </div>
                    <hr>
                    <!-- End InvoiceTop-->
                    <div class="row">
                        <div class="col-sm-12">
                          <div class="card">
                            <div class="card-body">
                              <form class="needs-validation" action="{{route('manager.customer.transfert.save')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                  {{-- <h4 class="media-heading" style="font-size: 15px">Informations sur l'expéditeur</h4> --}}
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Téléphone du client</label>
                                    <input readonly class="form-control input-air-primary @error('customer_number') is-invalid @enderror" value="{{$phone_number}}" name="customer_number" id="customer_number" type="text" required="">
                                    @error('customer_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Prénom du client</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_first') is-invalid @enderror" name="receiver_first"  value="{{$firstname}}" id="receiver_first" type="text" required="">
                                    @error('receiver_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Nom du client</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_last') is-invalid @enderror" name="receiver_last"  value="{{$lastname}}" id="receiver_last" type="text" required="">
                                    @error('receiver_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-12 mt-4">
                                    <label class="form-label" for="validationCustom01">Transférer argent à</label>
                                    <select name="receiver_number" class="form-select input-air-primary digits @error('receiver_number') is-invalid @enderror" id="exampleFormControlSelect9">
                                      <option value=""></option>
                                      @foreach ($receiver as $users )
                                        <option value="{{ $users->phone }}">{{ $users->firstname." ".$users->lastname." - ".$users->phone        }}</option>
                                      @endforeach
                                    </select>
                                    @error('receiver_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>   

                                  <div class="col-md-6">
                                    <label class="form-label" for="compte">Compte Expéditeur à débiter</label>
                                    <select name="compte" class="form-select input-air-primary digits @error('compte') is-invalid @enderror" id="compte">
                                      <option selected disabled>Choisir le compte</option>
                                      <option value="current">Compte Principal</option>
                                      <option value="saving">Compte Epargne</option>
                                    </select>
                                    @error('compte')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-6">
                                    <label class="form-label" for="compte">Compte Bénéficiaire à créditer</label>
                                    <select name="compte_receiver" class="form-select input-air-primary digits @error('compte_receiver') is-invalid @enderror">
                                      <option selected disabled>Choisir le compte</option>
                                      <option value="current">Compte Principal</option>
                                      <option value="saving">Compte Epargne</option>
                                    </select>
                                    @error('compte_receiver')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">N° compte</label>
                                    <input readonly class="form-control input-air-primary" name="acnumber" value="" id="c_acnumber" type="text">
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Solde disponible(CDF)</label>
                                    <input readonly class="form-control input-air-primary" name="balance_cdf" value="" id="c_balance_cdf" type="text">
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Solde disponible(USD)</label>
                                    <input readonly class="form-control input-air-primary" name="balance_usd" value="" id="c_balance_usd" type="text">
                                  </div>

                        
                                  
                                  <hr>
                                  <h4 class="media-heading" style="font-size: 15px">Détails de la transaction</h4>

                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant du retrait</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" placeholder="" name="amount" id="amount" type="text" required="">
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Devise de la transaction</label>
                                    <select name="currency" class="form-select input-air-primary digits @error('currency') is-invalid @enderror" id="exampleFormControlSelect9" required>
                                      <option selected disabled>Choisir une devise</option>
                                      <option value="CDF">CDF</option>
                                      <option value="USD">USD</option>
                                    </select>
                                    @error('currency')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Frais de retrait</label>
                                    <input class="form-control input-air-primary @error('fees') is-invalid @enderror" name="fees" id="fees" type="text" >
                                    @error('fees')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                 
                                </div>
                
                                <div class="mb-3">
                                </div>
                                <button class="btn btn-primary mt-3" type="submit">Valider</button>
                              </form>
                            </div>
                          </div>
                
                        </div>
                    </div>
                    <div class="row">
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
                    <!-- End Invoice Mid-->
                    <div>
  
                    </div>
                    <!-- End InvoiceBot-->
                  </div>
                  <!-- Container-fluid Ends-->
                </div>
              </div>
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


<script type="text/javascript">

    $('#compte').on('change', function() {
      
      var compte = $(this).val();
      
  
      if (compte == "current") {
        document.getElementById("c_acnumber").value = {!! $cnumber !!};
        document.getElementById("c_balance_cdf").value = {!! $c_bcdf !!};
        document.getElementById("c_balance_usd").value = {!! $c_busd !!};
      } else {
  
        document.getElementById("c_acnumber").value = {!! $snumber !!};
        document.getElementById("c_balance_cdf").value = {!! $s_bcdf !!};
        document.getElementById("c_balance_usd").value = {!! $s_busd !!};
      }
      
    });
    
  </script>

  
  <script type="text/javascript">
      var amount = document.getElementById("amount");
    //   var money = document.getElementById("money");
      function add_number() {
          var frais = parseFloat(amount.value*(1/100));
        //   var frais = 0;
        //   var argent = parseFloat(money.value*1);
          if (isNaN(frais)) frais = 0;
        //   var montantPercu = parseFloat(amount.value*1);
        //   var total = argent - (montantPercu + frais);
        //   var netPayer = montantPercu + frais;
        //   if (isNaN(total)) total = 0;
        //   if (isNaN(netPayer)) netPayer = 0;
          document.getElementById("fees").value = frais;
        //   document.getElementById("remise").value = total;
        //   document.getElementById("net").value = netPayer;
      }
  </script>

    @endsection
@endsection