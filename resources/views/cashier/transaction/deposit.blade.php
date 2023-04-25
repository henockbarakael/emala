@extends('layouts.master')
@section('content')
@section('title','Tous les dépôts')
@section('page','Tous les dépôts')
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
            <h5>Tous les dépôts</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th hidden>Id</th>
                    <th class="text-left">Bénéficiaire</th>
                    <th class="text-left">Montant</th>
                    <th class="text-left">Frais</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Référence</th>
                    <th class="text-center">Agence</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($transactions as $key => $value)
                @php
                    if($value->currency_id == 1){
                      $currency = "CDF";
                    }
                    elseif($value->currency_id == 2){
                      $currency = "USD";
                    }
                    $phone = $value->phone;
                    $check_phone = DB::connection('mysql2')->table('users')->where('phone', $phone)->first();
                @endphp
                  <tr>
                    <td class="level text-left">{{$value->created_at}}</td>
                    <td hidden class="id">{{$value->id}}</td>
                    @if ($check_phone == null)
                    <td class="type text-left">{{$value->phone}}</td>
                    @else
                    <td class="type text-left"><a href="{{ url('cashier/compte-client-phone/'.Crypt::encrypt($value->phone)) }}">{{$value->phone}}</a></td>
                    @endif
                    <td class="balance_cdf text-left">{{$value->amount}}</td>
                    <td class="balance_usd text-left">{{$value->fees}}</td>
                    <td class="balance_usd text-left">{{$currency}}</td>
                    <td class="level text-left">{{$value->transaction_id}}</td>
                    <td class="balance_usd text-left">{{$value->btownship}}</td>
                    <td class="level text-center">{{$value->status}}</td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Date</th>
                    <th hidden>Id</th>
                    <th class="text-left">Bénéficiaire</th>
                    <th class="text-left">Montant</th>
                    <th class="text-left">Frais</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Référence</th>
                    <th class="text-center">Agence</th>
                    <th class="text-center">Status</th>
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
            <form action="" method="POST">
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