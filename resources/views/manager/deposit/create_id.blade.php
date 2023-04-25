@extends('layouts.master')
@section('content')
@section('title','Emala - Dépôt')
@section('page','Dépôt Interne')
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
                              <h4 class="media-heading">Dépôt Interne (Cash)</h4>
                              <p>Entrez le numéro de téléphone du client, puis ajoutez un montant avec la devise pour envoyer de l'argent en toute sécurité.<br><span></span></p>
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
                              <form class="needs-validation" action="{{route('manager.internal.deposit.store')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                  {{-- <h4 class="media-heading" style="font-size: 15px">Informations sur l'expéditeur</h4> --}}
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Téléphone Bénéficiaire</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_phone') is-invalid @enderror" value="{{$user->phone}}" name="receiver_phone" id="receiver_phone" type="text" required="">
                                    @error('receiver_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Prénom Bénéficiaire</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_first') is-invalid @enderror" name="receiver_first"  value="{{$user->firstname}}" id="receiver_first" type="text" required="">
                                    @error('receiver_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Nom Bénéficiaire</label>
                                    <input readonly class="form-control input-air-primary @error('receiver_last') is-invalid @enderror" name="receiver_last"  value="{{$user->name}}" id="receiver_last" type="text" required="">
                                    @error('receiver_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-12">
                                    <label class="form-label" for="compte">Dans quel compte voulez-vous effectuer ce dépôt?</label>
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
                                  {{-- <h4 class="media-heading mt-4" style="font-size: 15px">Informations sur le bénéficiaire</h4> --}}
                                  {{-- <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Téléphone Expéditeur</label>
                                    <input value="" onkeyup="GetDetail(this.value)" class="typeahead form-control input-air-primary @error('receiver_phone') is-invalid @enderror" name="receiver_phone" id="search" type="text" required="">
                                    <input value="" data-type="sender_phone" class="typeahead form-control autocomplete_txt input-air-primary @error('sender_phone') is-invalid @enderror" name="sender_phone" id="sender_phone_1" type="text" >
                                    @error('sender_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Prénom Expéditeur</label>
                                    <input  data-type="sender_first" class="form-control autocomplete_txt input-air-primary @error('sender_first') is-invalid @enderror" name="sender_first" id="sender_first_1" type="text" >
                                    @error('sender_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Nom Expéditeur</label>
                                    <input data-type="sender_last"  class="typeahead form-control autocomplete_txt input-air-primary @error('sender_last') is-invalid @enderror" name="sender_last" id="sender_last_1" type="text" >
                                    @error('sender_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div> --}}
                                  <div class="col-md-12 mt-4">
                                    <label class="form-label" for="validationCustom01">Référence</label>
                                    <input class="form-control input-air-primary @error('reference') is-invalid @enderror" name="reference" id="reference_1" type="text" >
                                    @error('reference')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  
                                  <hr>
                                  <h4 class="media-heading" style="font-size: 15px">Détails de la transaction</h4>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant perçu</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('money') is-invalid @enderror" name="money" value="{{old('money')}}" id="money" type="text" required="">
                                    @error('money')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant du dépôt</label>
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
                                    <select name="currency" class="form-select input-air-primary digits" id="exampleFormControlSelect9" required>
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
                                    <label class="form-label" for="validationCustom01">Frais de dépôt</label>
                                    <input class="form-control input-air-primary @error('fees') is-invalid @enderror" name="fees" id="fees" type="text" >
                                    @error('fees')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Total à payer</label>
                                    <input readonly class="form-control input-air-primary @error('net') is-invalid @enderror" name="net" id="net" type="text"  >
                                    @error('net')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Remboursement</label>
                                    <input readonly class="form-control input-air-primary @error('remise') is-invalid @enderror" value="{{old('remise')}}" name="remise" id="remise" type="text"  >
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
                        <div class="col-sm-12">
                          <div class="card">
                            <div class="card-body">
                              <div class="table-responsive">
                                <table class="display" id="orderingById">
                                  <thead>
                                    <tr>
                                      <th>Date transaction</th>
                                      <th hidden>Id</th>
                                      <th class="text-left">Montant</th>
                                      {{-- <th class="text-left">Devise</th> --}}
                                      {{-- <th class="text-left">Frais</th> --}}
                                      <th class="text-left">Motif du dépôt</th>
                                      <th class="text-left">Reference</th>
                                      {{-- <th class="text-center">Agence</th> --}}
                                      <th class="text-center">Status</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                  @foreach ($transaction as $key => $value)
                                  @php
                                      if($value->currency_id == 1){
                                        $currency = "CDF";
                                      }
                                      elseif($value->currency_id == 2){
                                        $currency = "USD";
                                      }
                                  @endphp
                                    <tr>
                                      <td class="level">{{$value->created_at}}</td>
                                      <td hidden class="id">{{$value->id}}</td>
                                      {{-- <td class="type text-center"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->sender_phone)) }}">{{$value->sender_phone}}</a></td> --}}
                                      <td class="balance_cdf text-left">{{$value->amount." ".$currency}}</td>
                                      {{-- <td class="balance_usd text-center">{{$currency}}</td> --}}
                                      {{-- <td class="balance_usd text-center">{{$value->fees}}</td> --}}
                                      {{-- <td class="type text-center"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->receiver_phone)) }}">{{$value->receiver_phone}}</a></td> --}}
                                      <td class="level text-left">{{$value->note}}</td>
                                      <td class="level text-left">{{$value->reference}}</td>
                                      {{-- <td class="level text-center">{{$value->btownship}}</td> --}}
                                      <td class="level text-center">{{$value->status}}</td>
                                    </tr>
                                  @endforeach
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <th>Date transaction</th>
                                      <th hidden>Id</th>
                                      <th class="text-left">Montant</th>
                                      {{-- <th class="text-left">Devise</th> --}}
                                      {{-- <th class="text-left">Frais</th> --}}
                                      <th class="text-left">Motif du dépôt</th>
                                      <th class="text-left">Reference</th>
                                      {{-- <th class="text-center">Agence</th> --}}
                                      <th class="text-center">Status</th>
                                    </tr>
                                  </tfoot>
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

      $('#compte').on('change', function() {
        
        var value = $(this).val();
        var solde_cdf = {!! $solde_cdf !!};
        var solde_usd = {!! $solde_usd !!};
        var solde1 = "";
        var solde2 = "";

        if (solde_cdf == null) {
            solde1 = "-";
        } else {
            solde1 = {!! $solde_cdf !!};
        } 
        if (solde_usd == null) {
            solde2 = "-";
        } else {
            solde2 = {!! $solde_usd !!};
        }

        if (value == "current") {
          document.getElementById("c_acnumber").value = {!! $acnumber !!};
          document.getElementById("c_balance_cdf").value = {!! $balance_cdf !!};
          document.getElementById("c_balance_usd").value = {!! $balance_usd !!};
        } else {

          document.getElementById("c_acnumber").value = "12222";
          document.getElementById("c_balance_cdf").value = {!! $solde_cdf !!};
          document.getElementById("c_balance_usd").value = {!! $solde_usd !!};
        }
        // alert(value);
      });
      
    </script>
    <script type="text/javascript">
      //autocomplete script
      $(document).on('focus','.autocomplete_txt',function(){
        type = $(this).data('type');
        
        if(type =='sender_phone' )autoType='phone'; 
        if(type =='sender_first' )autoType='firstname'; 
        if(type =='sender_last' )autoType='lastname'; 
      
        $(this).autocomplete({
            minLength: 0,
            source: function( request, response ) {
                  $.ajax({
                      url: "{{ route('manager.search_autocomplete') }}",
                      dataType: "json",
                      data: {
                          term : request.term,
                          type : type,
                      },
                      success: function(data) {
                          var array = $.map(data, function (item) {
                            return {
                                label: item[autoType],
                                value: item[autoType],
                                data : item
                            }
                        });
                          response(array)
                      }
                  });
            },
            select: function( event, ui ) {
                var data = ui.item.data;           
                id_arr = $(this).attr('id');
                id = id_arr.split("_");
                elementId = id[id.length-1];
                $('#sender_phone_'+elementId).val(data.phone);
                $('#sender_first_'+elementId).val(data.firstname);
                $('#sender_last_'+elementId).val(data.lastname);
            }
        });
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
            var netPayer = montantPercu + frais;
            if (isNaN(total)) total = 0;
            if (isNaN(netPayer)) netPayer = 0;
            document.getElementById("fees").value = frais;
            document.getElementById("remise").value = total;
            document.getElementById("net").value = netPayer;
        }
    </script>
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