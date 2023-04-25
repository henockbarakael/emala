@extends('layouts.master')
@section('content')
@section('title','Gestion des caissiers')
@section('page','Liste des caissiers')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row mt-2 mb-4">
            <div style="height: 30px">
              <a href="#" class="btn btn-success btn-sm pull-right"  title="Ajouter un caissier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_treller"><i class="fa fa-user-plus" style="margin-right: 3px"></i><span >Ajouter un caissier</span> </a>
            </div>
          </div>
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Avatar</th>
                    <th hidden>ID</th>
                    <th hidden>Email</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Mot de passe</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($users as $key => $value)
                  <tr>
                    <td class="avatar"><div class="d-inline-block align-middle"><img class="img-40 m-r-15 rounded-circle align-top" src="{{ URL::to('/assets/images/'. $value->avatar) }}" alt=""></td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td hidden class="email">{{$value->email}}</td>
                    <td class="firstname">{{$value->firstname}}</td>
                    <td class="lastname">{{$value->lastname}}</td>
                    <td class="phone_number"><a href="{{url('cashier/profil-utilisateur/'.Crypt::encrypt($value->phone_number))}}">{{$value->phone_number}}</a></td>
                    <td class="role_name">{{$value->role_name}}</td>
                    <td class="salt">{{$value->password_salt}}</td>
                    <td>
                        <a href="" class="btn btn-primary btn-xs userUpdate" title="Edit cashier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-edit"></i></a>
                        <a href="" class="btn btn-secondary btn-xs userDelete" title="Delete cashier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Avatar</th>
                    <th hidden>ID</th>
                    <th hidden>Email</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Mot de passe</th>
                    <th>Action</th>
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
  <div class="modal fade" id="add_treller" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Ajouter un caissier</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('cashier.branche.store.treller')}}" method="POST">
                @csrf
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
            <form action="{{route('cashier.supprimer_caissier')}}" method="POST">
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
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#edit_user').modal('show');
    </script>
    @endif
    @endsection
@endsection