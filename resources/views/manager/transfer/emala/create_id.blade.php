@extends('layouts.master')
@section('content')
@section('title','Emala - Transfert')
@section('page','Transfert Interne')
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
                              <h4 class="media-heading">Transfert Interne</h4>
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
                              <form class="needs-validation" action="{{route('manager.transfer.emala_interne.store')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                  {{-- <h4 class="media-heading" style="font-size: 15px">Informations sur l'expéditeur</h4> --}}
                                  
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Téléphone Expéditeur</label>
                                    <input readonly class="form-control input-air-primary @error('sender_phone') is-invalid @enderror" value="{{$user->phone}}" name="sender_phone" id="sender_phone" type="text" required="">
                                    @error('sender_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Prénom Expéditeur</label>
                                    <input readonly class="form-control input-air-primary @error('sender_first') is-invalid @enderror" name="sender_first"  value="{{$user->firstname}}" id="sender_first" type="text" required="">
                                    @error('sender_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Nom Expéditeur</label>
                                    <input readonly class="form-control input-air-primary @error('sender_last') is-invalid @enderror" name="sender_last"  value="{{$user->name}}" id="sender_last" type="text" required="">
                                    @error('sender_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-12">
                                    <label class="form-label" for="compte">Compte à débiter</label>
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
                                  
                                  <hr>
                                  {{-- <h4 class="media-heading mt-4" style="font-size: 15px">Informations sur le Expéditeur</h4> --}}
                                  

                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Téléphone Bénéficiaire</label>
                                    {{-- <input value="" onkeyup="GetDetail(this.value)" class="typeahead form-control input-air-primary @error('receiver_phone') is-invalid @enderror" name="receiver_phone" id="search" type="text" required=""> --}}
                                    <input value="" data-type="receiver_phone" class="typeahead form-control autocomplete_txt input-air-primary @error('receiver_phone') is-invalid @enderror" name="receiver_phone" id="receiver_phone_1" type="text" >
                                    @error('receiver_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Prénom Bénéficiaire</label>
                                    <input  data-type="receiver_first" class="form-control autocomplete_txt input-air-primary @error('receiver_first') is-invalid @enderror" name="receiver_first" id="receiver_first_1" type="text" >
                                    @error('receiver_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Nom Bénéficiaire</label>
                                    <input data-type="receiver_last"  class="typeahead form-control autocomplete_txt input-air-primary @error('receiver_last') is-invalid @enderror" name="receiver_last" id="receiver_last_1" type="text" >
                                    @error('receiver_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  
                                  <hr>
                                  <h4 class="media-heading" style="font-size: 15px">Détails de la transaction</h4>
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant du transfert</label>
                                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" placeholder="Montant de la transaction" name="amount" id="amount" type="text" required="">
                                    @error('amount')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                
                                  <div class="col-md-6 mt-4">
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
                
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Frais d'envois</label>
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

{{-- <script type="text/javascript">
  var path = "{{ route('manager.autocomplete') }}";
  $('#search').typeahead({
      source:  function (query, process) {
      return $.get(path, { term: query }, function (data) {
              return process(data);
          });
          }
      });
</script> --}}




<script type="text/javascript">
//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
  type = $(this).data('type');
  
  if(type =='receiver_phone' )autoType='phone'; 
  if(type =='receiver_first' )autoType='firstname'; 
  if(type =='receiver_last' )autoType='lastname'; 
  
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
           $('#receiver_phone_'+elementId).val(data.phone);
           $('#receiver_first_'+elementId).val(data.firstname);
           $('#receiver_last_'+elementId).val(data.lastname);
       }
   });
  });
</script>

<script type="text/javascript">
    var amount = document.getElementById("amount");
    function add_number() {
        var frais = parseFloat(amount.value*(3/100));
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