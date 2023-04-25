@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Liste des agences')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Liste des agences</h5>
            <span class="pull-right">
              <a href="{{route('root.branche.create')}}" class="btn btn-primary" type="button" data-bs-original-title="" title="">
                <span><i class="fa fa-plus text-white"></i></span> Ajouter une agence
              </a>
            </span>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Agence</th>
                    <th hidden>ID</th>
                    <th>Commune</th>
                    <th>E-mail</th>
                    <th>Ville</th>
                    <th>Type</th>
                    <th class="text-right">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($branches as $key => $value)
                  <tr>
                    <td class="bname">{{$value->bname}}</td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td class="btownship">{{$value->btownship}}</td>
                    <td class="bemail">{{$value->bemail}}</td>
                    <td class="bcity">{{$value->bcity}}</td>
                    <td class="btype">{{$value->btype}}</td>
                    <td class="text-right">
                        <a href="" class="btn btn-success btn-xs userUpdate" title="Modifier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="icofont icofont-edit"></i></a>
                        <a href="" class="btn btn-danger btn-xs userDelete" title="Supprimer" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Agence</th>
                    <th hidden>ID</th>
                    <th>Commune</th>
                    <th>Ville</th>
                    <th>Type</th>
                    <th class="text-right">Action</th>
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
  <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Modifier agence</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('root.branche.update')}}" method="POST">
                @csrf
                <input type="hidden" name="id" id="e_id" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="btownship">Commune</label>
                    <input class="form-control @error('btownship') is-invalid @enderror" name="btownship" id="e_btownship" type="text" value="" >
                    @error('btownship')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="bemail">E-mail</label>
                    <input class="form-control @error('bemail') is-invalid @enderror" name="bemail" id="e_bemail" type="text" value="" >
                    @error('bemail')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="bcity">Ville</label>
                    <input class="form-control @error('bcity') is-invalid @enderror" name="bcity" id="e_bcity" type="text" value="" >
                    @error('bcity')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="btype">Type</label>
                    <select name="btype" class="form-select digits" id="e_btype">
                      {{-- <option selected disabled>Choisir un type</option> --}}
                      <option value="Parent">Parent</option>
                      <option value="Inner">Inner</option>
                    </select>
                    @error('btype')
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
            <form action="{{route('root.branche.delete')}}" method="POST">
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
            $('#e_btownship').val(_this.find('.btownship').text());
            $('#e_bemail').val(_this.find('.bemail').text());
            $('#e_bcity').val(_this.find('.bcity').text());
            var btype = (_this.find(".btype").text());
            var _option = '<option selected value="' +btype+ '">' + _this.find('.btype').text() + '</option>'
            $( _option).appendTo("#e_btype");
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