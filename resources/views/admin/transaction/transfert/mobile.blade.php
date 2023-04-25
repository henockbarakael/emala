@extends('layouts.master')
@section('content')
@section('title','Transfert interne')
@section('page','Transfert interne')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header mb-0">
              <h5 style="text-transform: none">Transfert Wallet To Mobile</h5>
            </div>
            <div class="card-body">
              <form id="formulaire" class="needs-validation" action="javascript:void(0)">
               
                <div class="row g-3">
                  <input hidden class="form-control" id="userid" name="userid" value="{{$customer->user_id}}"  type="text">
                  <div class="col-md-6">
                    <label class="form-label" for="method">Type de transfert</label>
                    <select name="method" class="form-select input-air-primary digits" id="method">
                      <option selected disabled>Choisir la méthode</option>
                      <option value="wallet_to_momo">Wallet to Mobile Money</option>
                      <option value="cashier_to_momo">Agence to Mobile Money</option>
                    </select>
                    @error('method')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Montant du transfert</label>
                    <input  class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="amount" type="text" required="">
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label" for="customer_number">Envoyer à</label>
                    <input class="form-control input-air-primary @error('customer_number') is-invalid @enderror" placeholder="Téléphone du bénéficiaire" name="customer_number" id="customer_number" type="text"  required="">
                    @error('customer_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-3">
                    <label class="form-label" for="validationCustom01">Opérateur</label>
                    <select name="telco" class="form-select input-air-primary digits" id="telco">
                      <option selected disabled>Choisir un opérateur</option>
                      <option value="mpesa">M-pesa</option>
                        <option value="orange">Orange money</option>
                        <option value="airtel">Airtel money</option>
                    </select>
                    @error('telco')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-3">
                    <label class="form-label" for="validationCustom01">Devise</label>
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
                  
                </div>
                <button id="send_form"  class="btn btn-primary mt-3" type="submit">Valider</button>
              </form>
            </div>
          </div>
                  
        </div>
      </div>
    </div>
    <!-- Container-fluid Ends-->
  </div>

  <div class="modal fade" style="display: none" id="loader" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <div class="loader-box">
            <div class="loader-19"></div>
          </div>
        </div>
      </div>
    </div>
  </div>  
  
  @section('script')
    <script type="text/javascript">
      $("#send_form").click(function(e){
          e.preventDefault();
          let exptid = $('#userid').val();
          let method = $('#method').val();
          let amount = $('#amount').val();
          let currency = $('#currency').val();
          let customer_number = $('#customer_number').val();
          let telco = $('#telco').val();
          $.ajax({
            url: "{{route('admin.mobile.store')}}",
            type:"POST",
            data:{
              "_token": "{{ csrf_token() }}",
              exptid:exptid,
              customer_number:customer_number,
              amount:amount,
              currency:currency,
              method:method,
              telco:telco,
            },
            beforeSend:function () {
               $('#loader').show();
              $("#loader").modal("show");
            },

            success:function(response){
              $("#loader").hide();
              if (response.success == true) {
                $("#loader").modal("hide");
                  swal({
                      title: "Succès!",
                      text: response.data,
                      icon: "success"
                  }).then(function() {
                      window.location = "{{url('admin/list-of-customer')}}";
                  });
                  
              }
              else {
                $("#loader").modal("hide");
                swal({
                      title: "Erreur!",
                      text: response.data,
                      icon: "error"
                  }).then(function() {
                    window.location.reload();
                  });
              }
              
            },
            error: function(response) {
              $("#loader").modal("hide");
              swal("ErreurRR!", response.data, "error");
            },
            });
          });
  </script>
  @endsection
@endsection