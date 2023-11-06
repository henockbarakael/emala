@extends('layouts.master')
@section('content')
@section('title','Historique de demande')
@section('page','Historique de demande')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        {{-- <div class="row mt-2 mb-4">
            <div style="height: 30px">
              <a href="#" class="btn btn-success btn-sm pull-right"  title="Ajouter un caissier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_treller"><i class="fa fa-user-plus" style="margin-right: 3px"></i><span >Ajouter un caissier</span> </a>
            </div>
          </div> --}}
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th hidden>#</th>
                    <th>N° Ticket</th>
                    <th class="text-left">Montant</th>
                    {{-- <th class="text-left">Devise</th> --}}
                    {{-- <th class="text-left">Objet</th> --}}
                    <th class="text-left">Assigner à</th>
                    <th class="text-center">Status</th>
                    <th class="text-left">Date création</th>
                    <th class="text-left">Date réponse</th>
                    {{-- <th class="text-center">Action</th> --}}
                  </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $key => $value)
                @php
                    $admi = DB::table('users')->where('id', $value->assigned_id)->first();
                @endphp
                  <tr>
                    <td hidden class="id">{{$value->idaccount}}</td>
                    <td class="firstname">{{$value->request_id}}</td>
                    <td class="acnumber text-left">{{$value->amount." ".$value->currency}}</td>
                    {{-- <td class="currency text-left">{{$value->currency}}</td> --}}
                    {{-- <td class="account_level text-left">{{$value->subject}}</td> --}}
                    <td class="account_level text-left">{{$admi->firstname." ".$admi->lastname}}</td>
                    <td class="text-center">
                        @if ($value->status == "En attente")
                        <span class="badge badge-warning">{{$value->status}}</span>
                        @elseif ($value->status == "Approuvé")
                        <span class="badge badge-success">{{$value->status}}</span>
                        @endif
                        
                    </td>
                    <td class="account_level text-left">{{$value->created_at}}</td>
                    <td class="account_level text-left">{{$value->updated_at}}</td>
                    {{-- <td class="text-center">
                        <a href="" class="btn btn-primary btn-xs userUpdate" title="Edit admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-edit"></i></a>
                        <a href="" class="btn btn-secondary btn-xs userDelete" title="Delete admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_user"><i class="icofont icofont-ui-delete"></i></a>
                    </td> --}}
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th hidden>#</th>
                    <th>N° Ticket</th>
                    <th class="text-left">Montant</th>
                    {{-- <th class="text-left">Devise</th> --}}
                    {{-- <th class="text-left">Objet</th> --}}
                    <th class="text-left">Assigner à</th>
                    <th class="text-center">Status</th>
                    <th class="text-left">Date création</th>
                    <th class="text-left">Date réponse</th>
                    {{-- <th class="text-center">Action</th> --}}
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
            <form action="{{route('admin.branche.account.topup')}}" method="POST">
                @csrf
                <input type="hidden" name="account_id" id="account_id" value="">
                <input type="hidden" name="account_level" id="account_level" value="">
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
        $(document).on('click','.AccountTopUp',function()
        {
            var _this = $(this).parents('tr');
            $('#account_id').val(_this.find('.id').text());
            $('#account_level').val(_this.find('.account_level').text());
            $('#topup_currency').val(_this.find('.currency').text());
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