@extends('layouts.master')
@section('content')
@section('title','Authentification')
@section('page','Vérification du client')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body">
                <div class="alert alert-danger" role="alert" id="unknow_number" style="display: none" >
                    Ce numéro n'est pas reconnu dans le système, veuillez vérifier et reprendre! 
                </div>
                  
              <form id="SubmitForm" class="needs-validation"  novalidate="" >
                {{-- <form class="needs-validation" action="{{route('admin.saving.verify-post')}}" novalidate="" method="POST" onsubmit="showHide(); return false;"> --}}
                {{-- @csrf --}}
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

              <form id="Form2"  style="display:none" class="needs-validation"  novalidate="" >
                <div class="row g-3">
                   
                        <div class="col-md-4">
                          <label class="form-label" for="customber_number">Numéro de téléphone</label>
                          <input readonly value="" class="form-control input-air-primary @error('customer_number') is-invalid @enderror" id="customer_number" name="customer_number" type="text"  required="">
                          <span class="text-danger" id="mobileErrorMsg"></span>
                          {{-- <input onkeyup="GetDetail(this.value)" value="" class="form-control typeahead input-air-primary @error('customber_number') is-invalid @enderror" id="search" name="customber_number" type="text"  required=""> --}}
                          @error('customer_number')
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                              </span>
                          @enderror
                          <div class="valid-feedback">Looks good!</div>
                        </div>
                    
                    {{-- <div class="col-md-6">
                        <label class="form-label" for="username">Identifiant</label>
                        <input readonly class="form-control input-air-primary @error('username') is-invalid @enderror" name="username" id="username" type="text" value="" >
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                    </div> --}}
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom01">Prénom</label>
                        <input readonly class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="sender_firstname" type="text" value="" >
                        @error('firstname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="validationCustom02">Nom</label>
                        <input readonly class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="sender_lastname" type="text" value="" >
                        @error('lastname')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                </div>
                <button  class="btn btn-primary mt-3 continuer" type="button" onclick="window.location='{{ route('admin.saving.create') }}'">Continuer</button>
              </form>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>
  @section('script')
    <script type="text/javascript">
        var amount = document.getElementById("amount");
        var montant_percu = document.getElementById("montant_percu");
        function add_number() {
            var frais = parseFloat(amount.value*(2/100));
            if (isNaN(frais)) frais = 0;
            var montant = parseFloat(amount.value);
            var mt = parseFloat(montant_percu.value);
            var net_payer = montant + frais;
            document.getElementById("fees").value = frais;
            document.getElementById("total").value = net_payer;
            document.getElementById("difference").value = mt - net_payer;
        }
    </script>
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
                if (response.success == true){
                  $('#Form2').show();
                  $('#SubmitForm').hide();
                  $('#unknow_number').hide();
                  // console.log(response);
                  $('#sender_firstname').val(response.firstname);
                  $('#sender_lastname').val(response.lastname);
                  $('#username').val(response.username);
                  $('#customer_number').val(response.customer_number);
                }
                else {
                    $('#unknow_number').show();
                    $('#Form2').hide();
                  }
                    
                
              },
             
              });
            });
    </script>

  @endsection
@endsection