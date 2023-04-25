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
              <h5 style="text-transform: none">Transfert Interne</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.transfert_interne.store')}}" method="POST">
                @csrf
                <div class="row g-3">
                  <input hidden class="form-control" name="exptid" value="{{$exptid}}"  type="text">
                  <input hidden class="form-control" name="destid" value="{{$destid}}"  type="text">

                  <div class="col-md-6">
                    <label class="form-label" for="method">Type de transfert</label>
                    <select name="method" class="form-select input-air-primary digits" id="method">
                      <option selected disabled>Choisir la méthode</option>
                      <option value="wallet_to_wallet">Wallet E to Wallet D</option>
                      <option value="cashier_to_wallet">Agence to Wallet D</option>
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
                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="amount" type="text" required="">
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-6">
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

                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Frais d'envois</label>
                    <input readonly class="form-control input-air-primary @error('fees') is-invalid @enderror" name="fees" id="fees" type="text"  required="">
                    @error('fees')
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
    </div>
    <!-- Container-fluid Ends-->
  </div>
  @section('script')
    <script type="text/javascript">
        var amount = document.getElementById("amount");
        function add_number() {
            var frais = parseFloat(amount.value*(2/100));
            if (isNaN(frais)) frais = 0;
            var montant = parseFloat(amount.value);
            document.getElementById("fees").value = frais;
        }
    </script>
  @endsection
@endsection