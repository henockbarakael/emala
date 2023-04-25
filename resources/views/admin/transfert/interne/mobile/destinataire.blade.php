@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Liste des clients')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="col-sm-12 mt-0">
                    <div class="card">
                    <div class="media p-20">
                        <div class="media-body">
                        <h6 class="mt-0 mega-title-badge">INFORMATIONS SUR L'EXPEDITEUR<span class="badge badge-primary pull-right digits">TRANSFERT WALLET TO WALLET</span></h6>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row g-3 mt-3">
                    <div class="col-md-4">
                    <label class="form-label" for="phone">Téléphone</label>
                    <input readonly class="form-control input-air-primary @error('phone') is-invalid @enderror" name="phone" id="phone" type="text" value="{{ $sender->phone_number }}" >
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-4">
                    <label class="form-label" for="firstname">Prénom</label>
                    <input readonly class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="e_firstname" type="text" value="{{ $sender->firstname }}" >
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-4">
                    <label class="form-label" for="lastname">Nom</label>
                    <input readonly class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="e_lastname" type="text" value="{{ $sender->lastname }}" >
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                    </div>
                </div>
                <div class="col-sm-12 mt-5">
                    <div class="card">
                    <div class="media p-20">
                        <div class="media-body">
                        <h6 class="mt-0 mega-title-badge">INFORMATIONS SUR LE DESTINATAIRE</h6>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="alert alert-danger" role="alert" id="unknow_number" style="display: none" >
                    Ce numéro n'est pas reconnu dans le système, veuillez vérifier et reprendre! 
                </div>
                <form id="SubmitForm" class="needs-validation"  novalidate="" >
                    <div class="row g-3">
                      <div class="col-md-12">
                        <label class="form-label" for="phone_number">Numéro de téléphone</label>
                        <input value="" class="form-control input-air-primary @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" type="text"  required="">
                        <span class="text-danger" id="mobileErrorMsg"></span>
                        {{-- <input onkeyup="GetDetail(this.value)" value="" class="form-control typeahead input-air-primary @error('phone_number') is-invalid @enderror" id="search" name="phone_number" type="text"  required=""> --}}
                        @error('phone_number')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                      </div>
                    </div>
                    <button  class="btn btn-primary mt-3" type="submit">Valider</button>
                  </form>
    
                  <form id="Form2" action="javascript:void(0)" method="POST"   style="display:none" class="needs-validation"  novalidate="" >
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="amount">Numéro de téléphone</label>
                            <input value="" class="form-control input-air-primary @error('customer_number') is-invalid @enderror" id="customer_number" name="customer_number" type="text"  required="">
                            <span class="text-danger" id="mobileErrorMsg"></span>
                            @error('customer_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>                            
                        
                        <div class="col-md-6">
                            <label class="form-label" for="username">Identifiant</label>
                            <input readonly class="form-control input-air-primary @error('username') is-invalid @enderror" name="username" id="username" type="text" value="" >
                            @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom01">Prénom</label>
                            <input readonly class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="sender_firstname" type="text" value="" >
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="validationCustom02">Nom</label>
                            <input  readonly class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="sender_lastname" type="text" value="" >
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="amount">Montant</label>
                            <input value="" class="form-control input-air-primary @error('amount') is-invalid @enderror" id="amount" name="amount" type="text"  required="">
                            <span class="text-danger" id="mobileErrorMsg"></span>
                            @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="valid-feedback">Looks good!</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label" for="currency">Devise</label>
                            <select name="currency" class="form-select input-air-primary digits" id="currency">
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
                          {{-- onclick="window.location='{{ url('admin/create-customer') }}'" --}}
                    </div>
                    <button id="send_form"  class="btn btn-primary mt-3 continuer" type="submit" >Transférer</button>
                    
                  </form>

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
    <script type="text/javascript">
        $('#SubmitForm').on('submit',function(e){
            e.preventDefault();
            let phone_number = $('#phone_number').val();
            $.ajax({
              url: "{{route('admin.saving.verify-post')}}",
              type:"POST",
              data:{
                "_token": "{{ csrf_token() }}",
                phone_number:phone_number,

              },
              success:function(response){
                // $('#successMsg').show();
                $('#Form2').show();
                $('#SubmitForm').hide();
                $('#unknow_number').hide();
                $('#sender_firstname').val(response.firstname);
                $('#sender_lastname').val(response.lastname);
                $('#username').val(response.username);
                $('#customer_number').val(response.customer_number);
              },
              error: function(response) {
                $('#unknow_number').show();
                $('#Form2').hide();
                $('#mobileErrorMsg').text(response.responseJSON.errors.mobile);
              },
              });
            });
    </script>
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
        $("#send_form").click(function(e){
            e.preventDefault();
            let sender = $('#phone').val();
            let receiver = $('#customer_number').val();
            let amount = $('#amount').val();
            let currency = $('#currency').val();
            $.ajax({
              url: "{{route('admin.transfert_externe_data')}}",
              type:"POST",
              data:{
                "_token": "{{ csrf_token() }}",
                sender:sender,
                receiver:receiver,
                amount:amount,
                currency:currency,
              },

              success:function(response){
                if (response.success == true) {
                    swal({
                        title: "Succès!",
                        text: response.data,
                        icon: "success"
                    }).then(function() {
                        window.location = "{{url('admin/expediteur')}}";
                    });
                }
                else {
                    swal("Erreur!", response.data, "error");
                }
                
              },
              error: function(response) {
                swal("Erreur!", response.data, "error");
              },
              });
            });
    </script>
    {{-- delete js --}}
    <script type="text/javascript">
        var path = "{{ route('admin.autocomplete') }}";
        $('#search').typeahead({
            source:  function (query, process) {
            return $.get(path, { term: query }, function (data) {
                    return process(data);
                });
                }
            });
    </script>
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
    <script>
        function GetDetail(str) {
            if (str.length == 0) {
                document.getElementById("sender_firstname").value = "";
                document.getElementById("sender_lastname").value = "";
                return;
            }
            else {

                // Creates a new XMLHttpRequest object
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && 
                            this.status == 200) {
                        
                        var myObj = JSON.parse(this.responseText);

                        document.getElementById
                            ("sender_firstname").value = myObj[0];
                        document.getElementById
                            ("sender_lastname").value = myObj[1];
                        
                    }
                };
                // xhttp.open("GET", "filename", true);
                xmlhttp.open("GET", "http://127.0.0.1:8000/admin/request?sender_phone=" + str, true);
                // Sends the request to the server
                xmlhttp.send();
            }
        }
    </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection