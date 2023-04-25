@extends('layouts.master')
@section('content')
@section('title','Emala - Transfert')
@section('page','Virement bancaire')
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
                              <h4 class="media-heading">Virement de fonds</h4>
                              <p>Vous pouvez déposer dans vos portefeuilles en utilisant nos méthodes de paiement populaires. Remplissez correctement les détails et le montant que vous souhaitez déposer.<br><span></span></p>
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
                              <form class="needs-validation" action="{{route('manager.transfer.emala_virement.store')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Numéro Expéditeur</label>
                                    <select name="sender_number" class="form-select input-air-primary digits @error('sender_number') is-invalid @enderror" id="exampleFormControlSelect9">
                                      <option value=""></option>
                                      @foreach ($user as $users )
                                        <option value="{{ $users->phone }}">{{ $users->firstname." ".$users->name." - ".$users->phone }}</option>
                                      @endforeach
                                    </select>
                                    @error('sender_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Numéro Bénéficiaire</label>
                                    <select name="customer_number" class="form-select input-air-primary digits @error('customer_number') is-invalid @enderror" id="exampleFormControlSelect9">
                                      <option value=""></option>
                                      @foreach ($user as $users )
                                        <option value="{{ $users->phone }}">{{ $users->firstname." ".$users->name." - ".$users->phone }}</option>
                                      @endforeach
                                    </select>
                                    @error('customer_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  {{-- <div class="col-md-6 mt-4">
                                    <label class="form-label" for="compte">Compte de départ</label>
                                    <select name="compte_1" class="form-select input-air-primary digits @error('compte_1') is-invalid @enderror" id="compte_1">
                                      <option selected disabled>Choisir le compte</option>
                                      <option value="current">Compte Principal</option>
                                      <option value="saving">Compte Epargne</option>
                                    </select>
                                    @error('compte_1')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>

                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="compte">Compte d'arrivé</label>
                                    <select name="compte_2" class="form-select input-air-primary digits @error('compte_2') is-invalid @enderror" id="compte_2">
                                      <option selected disabled>Choisir le compte</option>
                                      <option value="current">Compte Principal</option>
                                      <option value="saving">Compte Epargne</option>
                                    </select>
                                    @error('compte_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div> --}}
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Argent perçu</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('money') is-invalid @enderror" name="money" value="{{old('money')}}" id="money" type="text" required="">
                                    @error('money')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant du transfert</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" value="{{old('amount')}}" id="amount" type="text" required="">
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Devise de la transaction</label>
                                    <select name="currency" class="form-select input-air-primary digits @error('currency') is-invalid @enderror" id="exampleFormControlSelect9">
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
                                    <label class="form-label" for="validationCustom01">Frais d'envois</label>
                                    <input readonly class="form-control input-air-primary @error('fees') is-invalid @enderror" value="{{old('fees')}}" name="fees" id="fees" type="text"  required="">
                                    @error('fees')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Remboursement</label>
                                    <input readonly class="form-control input-air-primary @error('remise') is-invalid @enderror" value="{{old('remise')}}" name="remise" id="remise" type="text"  required="">
                                    @error('remise')
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
    var money = document.getElementById("money");
    function add_number() {
        var frais = parseFloat(amount.value*(2/100));
        var argent = parseFloat(money.value*1);
        if (isNaN(frais)) frais = 0;
        var montantPercu = parseFloat(amount.value*1);
        var total = argent - (montantPercu + frais);
        if (isNaN(total)) total = 0;
        document.getElementById("fees").value = frais;
        document.getElementById("remise").value = total;
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