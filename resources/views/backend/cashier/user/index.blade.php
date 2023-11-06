@extends('layouts.master')
@push('style')
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portefeuille numérique à la pointe de la technologie, Emala vous permet de faire des transactions financières sécurisées.">
    <meta name="keywords" content="EMALA, Emala, emala, emalafintech, fintech">
    <meta property="og:image" content="http://dashboard.emalafintech.net/assets/img/logo.png" />
    <meta property="og:image:secure_url" content="https://dashboard.emalafintech.net/assets/img/logo.png" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="400" />
    <meta property="og:image:height" content="300" />
    <meta property="og:image:alt" content="Emala Fintech" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Henock BARAKAEL | barahenock@gmail.com | +243828584688">
    <link rel="icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('backend/images/icon1.png')}}" type="image/x-icon">
    <title>Liste des utilisateurs</title>
    <link href="{{ asset('dist/css?family=Rubik:400,400i,500,500i,700,700i&amp;display=swap')}}" rel="stylesheet">
    <link href="{{ asset('dist/css-1?family=Roboto:300,300i,400,400i,500,500i,700,700i,900&amp;display=swap')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/font-awesome.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/icofont.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/themify.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/flag-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/feather-icon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/sweetalert2.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/scrollbar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/date-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/datatables.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link
      href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css" rel="stylesheet"/>
    <style>.myshadow{
      box-shadow: 0 10px 5px -1px rgba(0,0,0,.2),0 15px 18px 0 rgba(0,0,0,.14),0 1px 14px 0 rgba(0,0,0,.12)!important;
      border-radius: 8px;
    }</style>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/datatable-extension.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/daterange-picker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/vendors/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/style.css')}}">
    <link id="color" rel="stylesheet" href="{{ asset('backend/css/color-1.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/css/responsive.css')}}">
    <link rel="stylesheet" href="{{ asset('backend/css/toastr.min.css') }}">
    <script src="{{ asset('backend/js/toastr_jquery.min.js') }}"></script>
    <script src="{{ asset('backend/js/toastr.min.js') }}"></script>
</head>
@endpush
@section('content')
@section('page','Utilisateurs')
@section('page_1','Utilisateurs')
@section('page_2','Liste des utilisateurs')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('sweetalert::alert')
    @include('layouts.page-title')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <!-- Flexible table width Starts-->
        
          <div class="row">
            <div class="row mt-2 mb-4">
                <div style="height: 30px">
                  <a href="#" class="btn btn-success btn-sm pull-right"  title="Ajouter un caissier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_user"><span><i class="fa fa-plus text-white"></i></span> Ajouter un caissier</a>
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
                      <th>Compte</th>
                      <th>Prénom</th>
                      <th>Nom</th>
                      <th>Téléphone</th>
                      <th>Fonction</th>
                      <th class="min-w-100px" style="min-width: 200px">Agence</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  @foreach ($users as $key => $value)
                    <tr>
                      <td class="avatar"><div class="d-inline-block align-middle"><img class="img-40 m-r-15 rounded-circle align-top" src="https://frontend.emalafintech.net/assets/profil/{{$value->avatar}}" width="40px" height="40px" alt=""></td>
                      <td hidden class="id">{{$value->id}}</td>
                      <td class="acnumber">{{$value->acnumber}}</td>
                    
                      <td class="firstname">{{$value->firstname}}</td>
                      <td class="lastname">{{$value->lastname}}</td>
              
                      
                      <td class="phone_number"><a href="{{ url('admin/compte-client/'.Crypt::encrypt($value->phone_number)) }}">{{$value->phone_number}}</a></td>
                      <td class="role_name">{{$value->role_name}}</td>
                      <td class="branche min-w-100px" style="min-width: 200px">{{$value->bname}}</td>
                      <td class="status"><div class="badge badge-light-success">{{$value->status}}</div></td>
                      <td>
                          <a href="" class="btn btn-success btn-xs userUpdate" title="Edit admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-edit"></i></a>
                          <a href="" class="btn btn-danger btn-xs userDelete" title="Delete admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <!-- Flexible table width  Ends-->
      </div>
      <!-- Container-fluid Ends-->
    </div>
    <div class="modal fade" id="add_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel2">Ajouter Un Caissier</h5>
          </div>
          <div class="modal-body">
              <form action="{{route('cashier.user.add')}}" method="POST">
                  @csrf
                  <input type="hidden" name="user_id" id="e_id" value="">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom01">Prénom</label>
                      <input class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" type="text" value="" >
                      @error('firstname')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom02">Nom</label>
                      <input class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname"  type="text" value="" >
                      @error('lastname')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>
  
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom01">Téléphone</label>
                      <input class="form-control input-air-primary @error('phone_number') is-invalid @enderror" name="phone_number"  type="tel" value="" >
                      @error('phone_number')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom02">E-mail</label>
                      <input class="form-control input-air-primary @error('email') is-invalid @enderror" name="email"  type="text" value="" >
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="role">Fonction</label>
                        <select name="role" value="{{ old('role') }}" class="form-select input-air-primary digits @error('role') is-invalid @enderror" >
                            <option selected="" disabled="" value="Cashier">Caissier</option>
                        </select>
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
    <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel2">Modifier User</h5>
          </div>
          <div class="modal-body">
              <form action="{{route('cashier.user.edit')}}" method="POST">
                  @csrf
                  <input type="hidden" name="user_id" id="e_iduser" value="">
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom01">Prénom</label>
                      <input class="form-control input-air-primary @error('firstname') is-invalid @enderror" name="firstname" id="e_firstname" type="text" value="" >
                      @error('firstname')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom02">Nom</label>
                      <input class="form-control input-air-primary @error('lastname') is-invalid @enderror" name="lastname" id="e_lastname" type="text" value="" >
                      @error('lastname')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>
  
                    <div class="col-md-6">
                      <label class="form-label" for="validationCustom01">Téléphone</label>
                      <input readonly class="form-control input-air-primary @error('phone_number') is-invalid @enderror" name="phone_number" id="e_phone_number" type="tel" value="" >
                      @error('phone_number')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                      <div class="valid-feedback">Looks good!</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="branche">Agence</label>
                        <select  name="branche" value="" class="form-select input-air-primary digits @error('branche') is-invalid @enderror" id="e_branche">
                            <option selected disabled value="{{ $branches->bname }}">{{ $branches->bname }}</option>  
                        </select>
                      @error('branche')
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
              <form action="{{route('cashier.user.delete')}}" method="POST">
                  @csrf
                  <input type="hidden" name="id" class="e_ids" value="">
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
  </div>

@section('script')
<script src="{{ asset('backend/js/jquery-3.5.1.min.js')}}"></script>
<script src="{{ asset('backend/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('backend/js/icons/feather-icon/feather.min.js')}}"></script>
<script src="{{ asset('backend/js/icons/feather-icon/feather-icon.js')}}"></script>
<script src="{{ asset('backend/js/scrollbar/simplebar.js')}}"></script>
<script src="{{ asset('backend/js/scrollbar/custom.js')}}"></script>
<script src="{{ asset('backend/js/config.js')}}"></script>
<script src="{{ asset('backend/js/sidebar-menu.js')}}"></script>
<script src="{{ asset('backend/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('backend/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ asset('backend/js/script.js')}}"></script>
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_firstname').val(_this.find('.firstname').text());
            $('#e_lastname').val(_this.find('.lastname').text());
            $('#e_phone_number').val(_this.find('.phone_number').text());
            var branche = (_this.find(".branche").text());
            var _option = '<option selected value="' +branche+ '">' + _this.find('.branche').text() + '</option>'
            $( _option).appendTo("#e_branche");
        });
        $(document).on('click','.userDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_ids').val(_this.find('.id').text());
        });
    </script>
    @endsection
@endsection