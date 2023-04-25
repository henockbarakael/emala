@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Liste des clients')
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
                        <div class="col-sm-6">
                          <div class="media">
                            <div class="media-left"><img class="media-object img-60" src="{{ URL::to('/assets/images/'. $sender->avatar) }}" alt=""></div>
                            <div class="media-body m-l-20 text-right">
                              <h4 class="media-heading">Expéditeur</h4>
                              <p>{{ $sender->firstname." ".$sender->lastname }}<br><span>{{$sender->phone_number}}</span></p>
                            </div>
                          </div>
                          <!-- End Info-->
                        </div>
                        <div class="col-sm-6">
                          <div class="text-md-end text-xs-center">
                            <h3>Compte #<span class="counter">{{$sender->acnumber}}</span></h3>
                            <p>
                                CDF: {{$sender->balance_cdf}}<br>                                                            
                                USD: {{$sender->balance_usd}}
                            </p>
                          </div>
                          <!-- End Title-->
                        </div>
                      </div>
                    </div>
                    <hr>
                    <!-- End InvoiceTop-->
                    <div class="row">
                      <div class="col-md-12">
                        <div class="text-md-start" id="project">
                            <h6 class="media-heading">Liste des clients emala</h6>
                          <div class="mt-3 alert alert-dark dark alert-dismissible fade show" role="alert">Sur la liste ci-dessous, veuillez sélectionner un destinataire (celui à qui l’argent doit être transféré). 
                            Sur la bare de recherche, vous pouvez rechercher un destinataire par son numéro de téléphone, prénom, nom ou encore par son numéro de compte.
                            {{-- <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button> --}}
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- End Invoice Mid-->
                    <div>
                        <div class="table-responsive mt-4">
                            <table class="display" id="destinataire">
                              <thead>
                                <tr>
                                  <th hidden>ID</th>
                                  <th>Prénom</th>
                                  <th>Nom</th>
                                  <th>Téléphone</th>
                                  <th>Compte</th>
                                  <th>Wallet</th>
                                  <th class="text-center">Action</th>
                                </tr>
                              </thead>
                              <tbody>
                              @foreach ($receivers as $key => $value)
                                <tr>
                                  <td hidden class="id">{{$value->id}}</td>
                                  <td class="firstname">{{$value->firstname}}</td>
                                  <td class="lastname">{{$value->lastname}}</td>
                                  <td class="phone_number">{{$value->phone_number}}</td>
                                  <td class="acnumber">{{$value->acnumber}}</td>
                                  <td class="wallet_id">{{$value->wallet_id}}</td>
                                  <td class="text-center">
                                      <a href="{{ url('admin/transfert/interne/'.Crypt::encrypt($sender->id). '/' .Crypt::encrypt($value->id) ) }}" class="btn btn-success btn-xs"><i class="fa fa-check-circle-o"></i></a>
                                  </td>
                                </tr>
                              @endforeach
                              </tbody>
                              <tfoot>
                                <tr>
                                  <th hidden>ID</th>
                                  <th>Prénom</th>
                                  <th>Nom</th>
                                  <th>Téléphone</th>
                                  <th>Compte</th>
                                  <th>Wallet</th>
                                  <th class="text-center">Action</th>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                      <!-- End Table-->
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
  <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Modifier Client</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.customer.update')}}" method="POST">
                @csrf
                <input type="hidden" name="user_id" id="e_id" value="">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Prénom</label>
                    <input class="form-control @error('firstname') is-invalid @enderror" name="firstname" id="e_firstname" type="text" value="" >
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom02">Nom</label>
                    <input class="form-control @error('lastname') is-invalid @enderror" name="lastname" id="e_lastname" type="text" value="" >
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Téléphone</label>
                    <input class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" id="e_phone_number" type="tel" value="" >
                    @error('phone_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  {{-- <div class="col-md-4">
                    <label class="form-label" for="validationCustom02">Adresse email</label>
                    <input class="form-control @error('email') is-invalid @enderror" name="email" id="e_email" type="email" value="" >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div> --}}
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Mot de passe</label>
                    <input class="form-control @error('password') is-invalid @enderror" name="password" id="e_password" type="text" value="" >
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom02">Confirmer mot de passe</label>
                    <input class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation"  type="text" value="" >
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                </div>
                
                <div class="modal-footer mt-5">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Valider</button>
                  </div>
              </form>
          </div>
          
      </div>
    </div>
  </div>
  <div class="modal fade" id="delete_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="text-center mt-4">
                <h3>Supprimer l'utilisateur</h3>
                <p>Etes-vous sûre de vouloir supprimer?</p>
            </div>
        </div>
        <div class="modal-btn">
            <form action="{{route('admin.customer.delete')}}" method="POST">
                @csrf
                <input type="hidden" name="id" class="e_id" value="">
                <div class="row">
                    <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-secondary" type="submit">Supprimer</button>
                      </div>
                </div>
            </form>
        </div>
      </div>
    </div>
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