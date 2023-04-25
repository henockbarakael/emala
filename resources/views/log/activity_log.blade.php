@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Journal d\'activités')
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
            <h5>Journal d'activités</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nom cmplet</th>
                    <th>E-mail</th>
                    <th>Description</th>
                    <th>Date</th>           
                  </tr>
                </thead>
                <tbody>
                    @foreach ($activityLog as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->date_time }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>E-mail</th>
                    <th>Description</th>
                    <th>Date</th>
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
            <h5 class="modal-title" id="exampleModalLabel2">Top up agence</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.branche.topup')}}" method="POST">
                @csrf
                <input type="hidden" name="agence_id" id="e_id" value="">
                <input type="hidden" name="currency" id="e_devise" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="exampleFormControlSelect9">Wallet</label>
                    <select name="gateway" class="form-select digits" id="exampleFormControlSelect9">
                      <option selected disabled>Choisir un wallet</option>
                      <option value="emala">Emala</option>
                      <option value="mobile-money">Mobile Money</option>
                    </select>
                    @error('gateway')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Montant</label>
                    <input class="form-control @error('amount') is-invalid @enderror" name="amount" id="e_amount" type="text" value="" >
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
            <form action="{{route('admin.branche.delete')}}" method="POST">
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
            $('#e_devise').val(_this.find('.currency').text());
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