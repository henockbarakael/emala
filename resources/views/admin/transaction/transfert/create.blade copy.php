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
              <h5 style="text-transform: none">Faire un transfert</h5>
            </div>
            <div class="card-body">
              <form class="needs-validation" action="{{route('admin.transfert.store')}}" method="POST">
                @csrf
                <div class="row g-3">
                  <input hidden class="form-control" name="acnumber" value="{{$acnumber}}"  type="text">
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Téléphone Expéditaire</label>
                    <input onkeyup="GetDetail(this.value)" value="" class="typeahead form-control input-air-primary @error('sender_phone') is-invalid @enderror" name="sender_phone" id="search" type="text"  required="">
                    @error('sender_phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <hr>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Montant du transfert</label>
                    <input oninput="add_number()" class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="amount" type="text" required="">
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Frais</label>
                    <input readonly class="form-control input-air-primary @error('fees') is-invalid @enderror" name="fees" id="fees" type="text"  required="">
                    @error('fees')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-4">
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

                  {{-- <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Prénom Expéditaire</label>
                    <input class="typeahead form-control @error('sender_firstname') is-invalid @enderror" value=""  name="sender_firstname" id="sender_firstname" type="text"  required="">
                    @error('sender_firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Nom Expéditaire</label>
                    <input class="typeahead form-control @error('sender_lastname') is-invalid @enderror" value=""  name="sender_lastname" id="sender_lastname" type="text"  required="">
                    @error('sender_lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div> --}}
                
                <hr>

                <div class="col-md-4">
                  <label class="form-label" for="validationCustom01">Montant perçu</label>
                  <input oninput="add_number()" class="form-control input-air-primary @error('montant_percu') is-invalid @enderror" name="montant_percu" id="montant_percu" type="text"  required="">
                  @error('montant_percu')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                  <div class="valid-feedback">Looks good!</div>
                </div>

                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Total</label>
                    <input readonly class="form-control input-air-primary @error('total') is-invalid @enderror" name="total" id="total" type="text"  required="">
                    @error('total')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Différence</label>
                    <input readonly class="form-control input-air-primary @error('difference') is-invalid @enderror" name="difference" id="difference" type="text"  required="">
                    @error('difference')
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
  
        // onkeyup event will occur when the user 
        // release the key and calls the function
        // assigned to this event
        function GetDetail(str) {
            if (str.length == 0) {
                document.getElementById("sender_firstname").value = "";
                // document.getElementById("sender_lastname").value = "";
                return;
            }
            else {
  
                // Creates a new XMLHttpRequest object
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function () {
  
                    // Defines a function to be called when
                    // the readyState property changes
                    if (this.readyState == 4 && 
                            this.status == 200) {
                          
                        // Typical action to be performed
                        // when the document is ready
                        var myObj = JSON.parse(this.responseText);
  
                        // Returns the response data as a
                        // string and store this array in
                        // a variable assign the value 
                        // received to first name input field
                          
                        document.getElementById
                            ("sender_firstname").value = myObj[0];
                        // document.getElementById
                        //     ("sender_lastname").value = myObj[1];
                          
                    }
                };
  
                // xhttp.open("GET", "filename", true);
                xmlhttp.open("GET", "http://127.0.0.1:9000/admin/request?sender_phone=" + str, true);
                  
                // Sends the request to the server
                xmlhttp.send();
            }
        }
    </script>

  @endsection
@endsection