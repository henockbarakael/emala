@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Wallet principal')
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
            <h5>Wallet Principal</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Wallet Code</th>
                    <th hidden>ID</th>
                    <th class="text-center">ID Compte</th>
                    <th class="text-center">Solde CDF</th>
                    <th class="text-center">Solde USD</th>
                    {{-- <th class="text-center">Type</th> --}}
                    {{-- <th class="text-center">Level</th> --}}
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($wallets as $key => $value)
                  <tr>
                    <td class="wallet_id">{{$value->wallet_id}}</td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td class="account_id text-center">{{$value->bank_account_id}}</td>
                    <td class="balance_cdf text-center">{{$value->balance_cdf}}</td>
                    <td class="balance_usd text-center">{{$value->balance_usd}}</td>
                    {{-- <td class="type text-center">{{$value->type}}</td> --}}
                    {{-- <td class="level text-center">{{$value->level}}</td> --}}
                    <td>
                        <a href="{{ url('gerant/recharge-compte/'.Crypt::encrypt($value->id)) }}" class="btn btn-success btn-xs userUpdate" title="Edit admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user">Top Up</a>
                        <a href="" class="btn btn-primary btn-xs modifierBalance" title="Modifier" data-bs-toggle="modal" data-original-title="test" data-bs-target="#modifier"><i class="fa fa-edit"></i> Modifier</a>

                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Wallet Code</th>
                    <th hidden>ID</th>
                    <th class="text-center">ID Compte</th>
                    <th class="text-center">Solde CDF</th>
                    <th class="text-center">Solde USD</th>
                    {{-- <th class="text-center">Type</th> --}}
                    {{-- <th class="text-center">Level</th> --}}
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
  <div class="modal fade" id="edit_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Top up wallet</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.wallet.topup')}}" method="POST">
                @csrf
                <input type="hidden" name="wallet_id" id="e_id" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="validationCustom01">Montant</label>
                    <input class="form-control input-air-primary @error('amount') is-invalid @enderror" name="amount" id="e_amount" type="text" value="" >
                    @error('amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="currency">Devise</label>
                    <select name="currency" class="form-select input-air-primary digits" id="currency">
                      <option selected disabled>Choisir une devise</option>
                      <option value="CDF">CDF</option>
                      <option value="USD">USD</option>
                      {{-- @foreach ($role_name as $role )
                        <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                      @endforeach --}}
                    </select>
                    @error('currency')
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

  <div class="modal fade" id="modifier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Modifier solde</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.wallet.update.balance')}}" method="POST">
                @csrf
                <input hidden type="text" name="wallet_id" id="e_idwallet" value="">
                <div class="row g-3">
                  <div class="col-md-6">
                    <label class="form-label" for="balance_usd">Solde USD</label>
                    <input class="form-control @error('balance_usd') is-invalid @enderror" name="balance_usd" id="e_balance_usd" type="text" value="" >
                    @error('balance_usd')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-6">
                    <label class="form-label" for="balance_cdf">Solde CDF</label>
                    <input class="form-control @error('balance_cdf') is-invalid @enderror" name="balance_cdf" id="e_balance_cdf" type="text" value="" >
                    @error('balance_cdf')
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
  @section('script')
    {{-- update js --}}
    <script>
        $(document).on('click','.userUpdate',function()
        {
            var _this = $(this).parents('tr');
            $('#e_id').val(_this.find('.id').text());
            $('#e_amount').val(_this.find('.amount').text());
        });
    </script>

<script>
  $(document).on('click','.modifierBalance',function()
  {
      var _this = $(this).parents('tr');
      $('#e_idwallet').val(_this.find('.id').text());
      $('#e_balance_cdf').val(_this.find('.balance_cdf').text());
      $('#e_balance_usd').val(_this.find('.balance_usd').text());
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