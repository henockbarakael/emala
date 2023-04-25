@extends('layouts.master')
@section('content')
@section('title','Recharge des agences')
@section('page','Recharge des agences')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <!-- Flexible table width Starts-->
      <div class="row mt-2 mb-4">
        <div style="height: 30px">
          <a href="#" class="btn btn-success btn-sm pull-right"  title="Ajouter agence" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_branche">Créer une agence</a>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Rechager une agence</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display table-bordered table-responsive" id="basic-8">
                <thead>
                  <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Gérant</th>
                    <th hidden>ID</th>
                    <th hidden>Level</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Devise</th>
                    <th class="text-center">Top Up</th>
                    <th class="text-center">Action</th>
                    {{-- <th>Editer</th> --}}
                    
                  </tr>
                </thead>
                <tbody>
                @foreach ($branches as $key => $value)
                
                
                  <tr>
                    
                    <td class="a_code">{{$value->bcode}}</td>
                    <td class="a_name">{{$value->bname}}</td>
                    <td class="fullname">{{$value->fullname}}</td>
                    <td hidden class="id">{{$value->idaccount}}</td>
                    <td hidden class="account_level text-center">{{$value->account_level}}</td>
                    <td class="balance text-center">{{$value->balance}}</td>
                    <td class="currency text-center">{{$value->currency}}</td>
                    <td class="text-center"><button class="btn btn-primary btn-xs AccountTopUp" title="Top Up Account" data-bs-toggle="modal" data-original-title="test" data-bs-target="#topup">Recharger</button></td>
                    <td class="text-center">
                        <a href="" class="btn btn-primary btn-xs userUpdate" title="Edit admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-edit"></i></a>
                        <a href="" class="btn btn-secondary btn-xs userDelete" title="Delete admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Code</th>
                    <th>Nom</th>
                    <th>Gérant</th>
                    <th hidden>ID</th>
                    <th hidden>Level</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Devise</th>
                    <th class="text-center">Top Up</th>
                    <th class="text-center">Action</th>
                    
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Flexible table width  Ends-->
    </div>
    <!-- Container-fluid Ends-->
  </div>
  <div class="modal fade" id="topup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Recharger le compte</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.branche.account.recharge')}}" method="POST">
                @csrf
                <input type="hidden" name="account_id" id="account_id" value="">
                {{-- <input type="hidden" name="account_level" id="account_level" value=""> --}}
                <input type="hidden" name="currency" id="topup_currency" value="">
                <div class="row g-3">
                  <div class="col-md-12">
                    <label class="form-label" for="validationCustom01">Montant</label>
                    <input class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="e_amount" type="text" value="" >
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
  <div class="modal fade" id="add_branche" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Ajouter une nouvelle agence</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.branche.create')}}" method="POST">
                @csrf
                <input type="hidden" name="branche_id" id="e_id" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Nom</label>
                    <input class="form-control input-air-primary @error('bname') is-invalid @enderror" name="bname"  type="text" value="" >
                    @error('bname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Téléphone</label>
                    <input class="form-control input-air-primary @error('phone') is-invalid @enderror" name="phone" id="e_phone" type="text" value="" >
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">E-mail</label>
                    <input class="form-control input-air-primary @error('email') is-invalid @enderror" name="email" id="e_email" type="email" value="" >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Commune</label>
                    <input class="form-control input-air-primary @error('township') is-invalid @enderror" name="township" id="e_township" type="text" value="" >
                    @error('township')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Ville</label>
                    <input class="form-control input-air-primary @error('city') is-invalid @enderror" name="city" id="e_city" type="text" value="" >
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
  <div class="modal fade" id="edit_agence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Information de l'agence</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.branche.update')}}" method="POST">
                @csrf
                <input type="hidden" name="branche_id" id="a_id" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Nom</label>
                    <input class="form-control input-air-primary @error('bname') is-invalid @enderror" name="bname" id="a_name" type="text" value="" >
                    @error('bname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Téléphone</label>
                    <input class="form-control input-air-primary @error('phone') is-invalid @enderror" name="phone" id="a_phone" type="tel" value="" >
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">E-mail</label>
                    <input class="form-control input-air-primary @error('email') is-invalid @enderror" name="email" id="a_email" type="email" value="" >
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Commune</label>
                    <input class="form-control input-air-primary @error('township') is-invalid @enderror" name="township" id="a_township" type="text" value="" >
                    @error('township')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Ville</label>
                    <input class="form-control input-air-primary @error('city') is-invalid @enderror" name="city" id="a_city" type="text" value="" >
                    @error('city')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>


                </div>
                
                <div class="modal-footer mt-5">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Modifier</button>
                  </div>
              </form>
          </div>
          
      </div>
    </div>
  </div>
  {{-- <div class="modal fade" id="assignUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Assigner un gérant</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.branche.assign')}}" method="POST">
                @csrf
                <div class="row g-3">
                  <div class="col-md-12">
                  <input type="hidden" name="branche_id" id="brancheID" value="">
                    <label class="form-label" for="id_gerant">Devise</label>
                    <select name="id_gerant" class="form-select input-air-primary digits" id="id_gerant">
                      <option selected disabled>Choisir un gérant</option>
                      @foreach ($users as $user )
                        <option value="{{ $user->id }}">{{ $user->firstname." ".$user->lastname}}</option>
                      @endforeach
                    </select>
                    @error('id_gerant')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
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
  </div> --}}
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
            <form action="" method="POST">
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
        $(document).on('click','.assignUser',function()
        {
            var _this = $(this).parents('tr');
            $('#brancheID').val(_this.find('.id').text());
        });
    </script>
    <script>
        $(document).on('click','.AccountTopUp',function()
        {
            var _this = $(this).parents('tr');
            $('#account_id').val(_this.find('.id').text());
            // $('#account_level').val(_this.find('.account_level').text());
            $('#topup_currency').val(_this.find('.currency').text());
        });
    </script>
    <script>
      $(document).on('click','.agenceUpdate',function()
      {
          var _this = $(this).parents('tr');
          $('#a_id').val(_this.find('.id').text());
          $('#a_name').val(_this.find('.a_name').text());
          $('#a_phone').val(_this.find('.a_phone').text());
          $('#a_email').val(_this.find('.a_email').text());
          $('#a_township').val(_this.find('.a_township').text());
          $('#a_city').val(_this.find('.a_city').text());
      });
  </script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.ids').text());
            $('.e_avatar').val(_this.find('.image').text());
        });
    </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection