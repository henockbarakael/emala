@extends('layouts.master')
@section('content')
@section('title','Emala - Transfert')
@section('page','Transfert Compte à Compte')
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
                              <h4 class="media-heading">Transfert compte principal - compte epargne</h4>
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
                              <form class="needs-validation" action="{{route('manager.account.transfert_current_saving')}}" method="POST">
                                @csrf
                                <div class="row g-3">
                                  {{-- <h4 class="media-heading" style="font-size: 15px">Informations sur l'expéditeur</h4> --}}
                                  
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Téléphone</label>
                                    <input readonly class="form-control input-air-primary @error('sender_phone') is-invalid @enderror" value="{{$user->phone}}" name="sender_phone" id="sender_phone" type="text" required="">
                                    @error('sender_phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Prénom</label>
                                    <input readonly class="form-control input-air-primary @error('sender_first') is-invalid @enderror" name="sender_first"  value="{{$user->firstname}}" id="sender_first" type="text" required="">
                                    @error('sender_first')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-2">
                                    <label class="form-label" for="validationCustom01">Nom</label>
                                    <input readonly class="form-control input-air-primary @error('sender_last') is-invalid @enderror" name="sender_last"  value="{{$user->name}}" id="sender_last" type="text" required="">
                                    @error('sender_last')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="valid-feedback">Looks good!</div>
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">N° compte principal</label>
                                    <input readonly class="form-control input-air-primary" name="acnumber" value="{{$acnumber}}" id="customer_number" type="text">
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Solde disponible(CDF)</label>
                                    <input readonly class="form-control input-air-primary" name="balance_cdf" value="{{$balance_cdf}}" id="balance_cdf" type="text">
                                  </div>
                                  <div class="col-md-4 mt-4">
                                    <label class="form-label" for="validationCustom01">Solde disponible(USD)</label>
                                    <input readonly class="form-control input-air-primary" name="balance_usd" value="{{$balance_usd}}" id="balance_usd" type="text">
                                  </div>

                                  <hr>
                                  <h4 class="media-heading" style="font-size: 15px">Détails de la transaction</h4>
                                  <div class="col-md-6 mt-4">
                                    <label class="form-label" for="validationCustom01">Montant du transfert</label>
                                    <input class="form-control input-air-primary @error('amount') is-invalid @enderror" placeholder="Montant de la transaction" name="amount" id="amount" type="text" required="">
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

    {{-- delete js --}}
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>

    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection