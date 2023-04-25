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
      
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="col-sm-12" style="padding: 10px">
                        <span class="pull-right">
                            <a href="{{route('admin.creation_client')}}" class="btn btn-success" type="button" data-bs-original-title="" title="">
                            <span><i class="fa fa-plus text-white"></i></span> Ajouter un client
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
 
      <div class="col-sm-12 mt-2">
        <div class="card">
       
          <div class="card-body">
            
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Avatar</th>
                    <th hidden>ID</th>
                    <th hidden>Email</th>
                    <th hidden>Adresse</th>
                    <th hidden>City</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th hidden>Mot de passe</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($customers as $key => $value)
                  <tr>
                    <td class="avatar"><div class="d-inline-block align-middle"><img class="img-40 m-r-15 rounded-circle align-top" src="{{ URL::to('/assets/images/'. $value->avatar) }}" alt=""></td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td hidden class="email">{{$value->email}}</td>
                    <td hidden class="address">{{$value->address}}</td>
                    <td hidden class="city">{{$value->city}}</td>
                    <td class="firstname">{{$value->firstname}}</td>
                    <td class="lastname">{{$value->name}}</td>
                    <td class="phone_number"><a href="{{ url('admin/profil-client/'.Crypt::encrypt($value->id)) }}">{{$value->phone}}</a></td>
                    <td hidden class="salt">{{$value->password_salt}}</td>
                    <td class="status"><div class="badge badge-light-success">{{$value->status}}</div></td>
                    <td>
                        <a href="" class="btn btn-success btn-xs userUpdate" title="Edit admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="icofont icofont-edit"></i></a>
                        <a href="" class="btn btn-danger btn-xs userDelete" title="Delete admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Avatar</th>
                    <th hidden>ID</th>
                    <th hidden>Email</th>
                    <th hidden>Adresse</th>
                    <th hidden>City</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th hidden>Mot de passe</th>
                    <th>Status</th>
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
  {{-- <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  </div> --}}
  {{-- <div class="modal fade" id="delete_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  </div> --}}
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