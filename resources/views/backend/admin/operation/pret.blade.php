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
    <title>Prêt Emala</title>
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
@section('page','Prêt Emala')
@section('page_1','Prêt Emala')
@section('page_2','Historique de prêt')
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
                              <h4 class="media-heading">Demande de prêt</h4>
                              {{-- <p>Entrez un montant, chosissez une devise pour effectuer un dépôt en toute sécurité.<br><span></span></p> --}}
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
                              <form  action="{{route('admin.pret.demande.post')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                  {{-- <h4 class="media-heading" style="font-size: 15px">Informations sur l'expéditeur</h4> --}}
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Téphone Bénéficiaire</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_phone') is-invalid @enderror" value="{{$phone_number}}" name="receiver_phone" id="receiver_phone" type="text" required="">
                                    @error('receiver_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Prénom Bénéficiaire</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_first') is-invalid @enderror" name="receiver_first"  value="{{$firstname}}" id="receiver_first" type="text" required="">
                                    @error('receiver_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Nom Bénéficiaire</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_last') is-invalid @enderror" name="receiver_last"  value="{{$lastname}}" id="receiver_last" type="text" required="">
                                    @error('receiver_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <hr>
                                  <h4 class="media-heading" style="font-size: 15px">Détails du prêt</h4>
                                  
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Capital à emprunter</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('loan_amount') is-invalid @enderror" placeholder="" name="loan_amount" id="loan_amount" type="number" required="">
                                    @error('loan_amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Nombre de mois</label>
                                    <input oninput="add_number()" hidden class="form-control input-air-primary @error('periodic_rate') is-invalid @enderror" placeholder="" name="periodic_rate" id="periodic_rate" type="text">
                                    <input oninput="add_number()" class="form-control input-air-primary @error('loan_duration') is-invalid @enderror" placeholder="" name="loan_duration" id="loan_duration" type="number" required="">
                                    @error('loan_duration')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Echéance de paiement</label>
                                    <select oninput="add_number()" name="echeance" class="form-select input-air-primary digits @error('echeance') is-invalid @enderror" id="echeance" required>
                                      <option selected disabled>Choisir une échéance</option>
                                      <option value="jour">Jour</option>
                                      <option value="semaine">Semaine</option>
                                      <option value="mois">Mois</option>
                                    </select>
                                    @error('echeance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                

                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant à verser / Echéance</label>
                                    <input readonly class="form-control input-air-primary @error('paid_by_echeance') is-invalid @enderror" name="paid_by_echeance" id="paid_by_echeance" type="text" >
                                    @error('paid_by_echeance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant à rembourser</label>
                                    <input readonly class="form-control input-air-primary @error('principal_paid') is-invalid @enderror" name="principal_paid" id="principal_paid" type="text" >
                                    @error('principal_paid')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Devise</label>
                                    <select name="loan_currency" class="form-select input-air-primary digits @error('loan_currency') is-invalid @enderror" id="loan_currency" required>
                                      <option selected disabled>Choisir une devise</option>
                                      <option value="CDF">CDF</option>
                                      <option value="USD">USD</option>
                                    </select>
                                    @error('loan_currency')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-12 mt-4">
                                    <label class="form-label" for="validationCustom01">Objet</label>
                                    <textarea class="form-control input-air-primary @error('objet') is-invalid @enderror" name="objet" id="objet" type="text" ></textarea>
                                    @error('objet')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <button class="btn btn-primary mt-3" type="submit" name="submit">Valider</button>
                 
                                </div>
                
                              </form>
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
      var amount = document.getElementById("loan_amount");
      var month = document.getElementById('loan_duration');
      var rate = document.getElementById('periodic_rate');
      var echeance = document.getElementById('echeance');

      function add_number() {
          var c = parseFloat(amount.value);
          var n = parseFloat(month.value)
          var t = parseFloat((rate.value * 0.01 * n) / 12);
          var e = echeance[echeance.selectedIndex].value;
          
          var i = c * t;
          var v = c + i;
         
          if (isNaN(v)) v = 0;
          var amountByEcheance = 0
          if (e == "jour") {
            amountByEcheance = v/26
          }
          if (e == "semaine") {
            amountByEcheance = v/4
          }
          if (e == "mois") {
            amountByEcheance = v/n
          }
          document.getElementById("principal_paid").value = v;
          document.getElementById("paid_by_echeance").value = amountByEcheance;

      }
  </script>

    @endsection
@endsection