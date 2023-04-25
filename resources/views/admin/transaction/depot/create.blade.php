@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Liste des clients')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    
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
                        <div class="col-sm-6">
                          <div class="media">
                            <div class="media-left"><img class="media-object img-60" src="{{ URL::to('/assets/images/'. $receivers->avatar) }}" alt=""></div>
                            <div class="media-body m-l-20 text-right">
                              <h4 class="media-heading">Bénéficiaire</h4>
                              <p>{{ $receivers->firstname." ".$receivers->lastname }}<br><span>{{$receivers->phone_number}}</span></p>
                            </div>
                          </div>
                          <!-- End Info-->
                        </div>
                        <div class="col-sm-6">
                          <div class="text-md-end text-xs-center">
                            <h3>Compte #<span class="counter">{{$receivers->acnumber}}</span></h3>
                            <p>
                                CDF: {{$receivers->balance_cdf}}<br>                                                            
                                USD: {{$receivers->balance_usd}}
                            </p>
                          </div>
                          <!-- End Title-->
                        </div>
                      </div>
                    </div>
                    <hr>
                    <!-- End InvoiceTop-->
                    <div class="row">
                        <div class="col-sm-12">
                          <div class="card">
                            <div class="card-header mb-0">
                              <h5 style="text-transform: none">Dépôt Cash</h5>
                            </div>
                            <div class="card-body">
                              <form class="needs-validation" action="{{route('admin.depot.store')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                  {{-- {{-- <input hidden class="form-control" name="exptid" value="{{$exptid}}"  type="text"> --}}
                                  <input hidden class="form-control" name="userid" value="{{$userid}}"  type="text"> 
                
                                  <div class="col-md-12">
                                    <label class="form-label" for="compte">Dans quel compte voulez-vous effectuer ce dépôt?</label>
                                    <select name="compte" class="form-select input-air-primary digits" id="compte">
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
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant du transfert</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="amount" type="text" required="">
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Devise de la transaction</label>
                                    <select name="currency" class="form-select input-air-primary digits" id="exampleFormControlSelect9">
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
                
                                  {{-- <div class="col-md-6">
                                    <label class="form-label" for="validationCustom01">Frais d'envois</label>
                                    <input readonly class="form-control input-air-primary @error('fees') is-invalid @enderror" name="fees" id="fees" type="text"  required="">
                                    @error('fees')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div> --}}
                                </div>
                
                                <div class="mb-3">
                                </div>
                                <button class="btn btn-primary mt-3" type="submit">Valider</button>
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
      <!-- Flexible table width  Ends-->
    </div>
    <!-- Container-fluid Ends-->
  </div>

  @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_lastname').val(_this.find('.lastname').text());
            $('#e_firstname').val(_this.find('.firstname').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            $('#e_email').val(_this.find('.email').text());
            $('#e_password').val(_this.find('.salt').text());
        });
    </script>

<script type="text/javascript">
    var amount = document.getElementById("amount");
    function add_number() {
        var frais = parseFloat(amount.value*(2/100));
        if (isNaN(frais)) frais = 0;
        var montant = parseFloat(amount.value);
        document.getElementById("fees").value = frais;
    }
</script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
    <script type="text/javascript">
      $('.livesearch').select2({
          placeholder: 'Select movie',
          ajax: {
              url: '/ajax-autocomplete-search',
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  return {
                      results: $.map(data, function (item) {
                          return {
                              text: item.name,
                              id: item.id
                          }
                      })
                  };
              },
              cache: true
          }
      });
  </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection