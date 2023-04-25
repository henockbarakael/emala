@extends('layouts.master')
@section('content')
@section('title','Emala - Retrait')
@section('page','Retrait Emala - Emala')
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
                        <div class="col-sm-12">
                          <div class="media">
                            <div class="media-body m-l-20 text-right">
                              <h4 class="media-heading">Retrait d'Argent</h4>
                              <p>Entrez le numéro de téléphone du client, puis ajoutez un montant avec la devise pour retirer de l'argent en toute sécurité.<br><span></span></p>
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
                              <form class="needs-validation" action="{{route('manager.withdrawal.compte.store')}}" method="POST">
                                @csrf
                                <div class="row g-3">

                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Numéro du client</label>
                                    <input readonly class="form-control input-air-primary @error('customer_number') is-invalid @enderror" name="customer_number" value="{{$user->phone}}" id="customer_number" type="text"  required="">
                                    @error('customer_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">N° compte</label>
                                    <input readonly class="form-control input-air-primary" name="acnumber" value="{{$acnumber}}" id="customer_number" type="text">
                                  </div>
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Solde disponible(CDF)</label>
                                    <input readonly class="form-control input-air-primary" name="balance_cdf" value="{{$balance_cdf}}" id="balance_cdf" type="text">
                                  </div>
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Solde disponible(USD)</label>
                                    <input readonly class="form-control input-air-primary" name="balance_usd" value="{{$balance_usd}}" id="balance_usd" type="text">
                                  </div>
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="amount" type="text" required="">
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Devise</label>
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

                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Frais de retrait</label>
                                    <input class="form-control input-air-primary @error('fees') is-invalid @enderror" name="fees" id="fees" type="text" >
                                    @error('fees')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Total à payer</label>
                                    <input readonly class="form-control input-air-primary @error('net') is-invalid @enderror" name="net" id="net" type="text"  >
                                    @error('net')
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
        var frais = parseFloat(amount.value*(0.7/100));
        var argent = parseFloat(amount.value*1);
        if (isNaN(frais)) frais = 0;
        var total = argent + frais;
        if (isNaN(total)) total = 0;
        document.getElementById("fees").value = frais;
        document.getElementById("net").value = total;
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