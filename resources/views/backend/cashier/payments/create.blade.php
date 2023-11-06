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
    <title>Paiement de prêt</title>
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
@section('page','Paiement de prêt')
@section('page_1','Paiement de prêt')
@section('page_2','Effectuer un versement')
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
                              <h4 class="media-heading">Effectuer un versement</h4>
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
                            {{-- <form class="needs-validation" action="{{ route('cashier.payments.store', ['loanId' => $loan->id]) }}" method="POST"> --}}
                            <form class="needs-validation" action="{{ route('cashier.loans.make-payment', ['id' => $loan->id]) }}" method="POST">
                              
                              @csrf
                              <div class="row g-3">
                        
                                <div class="col-md-6 mt-4">
                                  <label class="form-label" for="validationCustom01">Montant du prêt</label>
                                  <input readonly value="{{$loan->amount}}" class="form-control input-air-primary @error('loan_amount') is-invalid @enderror" placeholder="" name="loan_amount" id="loan_amount" type="number" >
                                  @error('loan_amount')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                  <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-6 mt-4">
                                  <label class="form-label" for="validationCustom01">Reste à payer </label>
                                  <input readonly value="{{$loan->balance}}" class="form-control input-air-primary @error('remaining_balance') is-invalid @enderror" placeholder="" name="remaining_balance" id="remaining_balance" type="number">
                                  @error('remaining_balance')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                  <div class="valid-feedback">Looks good!</div>
                                </div>
                                <div class="col-md-6 mt-4">
                                  <label class="form-label" for="validationCustom01">Montant du paiement</label>
                                  <input class="form-control input-air-primary @error('amount') is-invalid @enderror" placeholder="" name="amount" id="amount" type="number">
                                  @error('amount')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                                  <div class="valid-feedback">Looks good!</div>
                                </div>

                                <div class="col-md-6 mt-4">
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
              
                                {{-- <div class="col-md-6 mt-4">
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
                                </div> --}}
                                <hr>
                              </div>
                              <button class="col-sm-12 btn btn-primary" type="submit">Valider</button>
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
<script src="{{ asset('backend/js/script.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js">
</script>
<script type="text/javascript">
    var path = "{{ route('client.rechercher') }}";
    $('#phone').typeahead({
        source: function (query, process) {
            return $.get(path, {
                query: query
            }, function (data) {
                return process(data);
            });
        },
        afterSelect: function (item) {
            var selectedPhone = item;
            $.ajax({
                url: "{{ route('client.details') }}",
                type: 'GET',
                data: { phone: selectedPhone },
                success: function (response) {
                    $('#surname').val(response.surname);
                    $('#name').val(response.name);
                }
            });
        }
    });
</script>
    @endsection
@endsection