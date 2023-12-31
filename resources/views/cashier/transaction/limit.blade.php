@extends('layouts.master')
@section('content')
@section('title','Limites Transactions')
@section('page','Limites Transactions')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <!-- Flexible table width Starts-->
      <div class="row mt-2 mb-4">
        <div style="height: 30px">
          <a href="#" class="btn btn-success btn-sm pull-right"  title="Ajouter limite" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_limit">Ajouter une limite</a>
        </div>
      </div>
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Limites Transactions</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th hidden>Id</th>
                    <th class="text-left">Transaction_type</th>
                    <th class="text-left">Montant min</th>
                    <th class="text-left">Montant max</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Limite/Jour</th>
                    <th hidden class="text-left">Limite/Semaine</th>
                    <th class="text-center">Status</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($limits as $key => $value)
                  <tr>
                    <td class="type_transaction text-left">{{$value->type_transaction}}</td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td class="min_amount text-left">{{$value->min_amount}}</td>
                    <td class="max_amount text-left">{{$value->max_amount}}</td>
                    <td class="currency text-left">{{$value->currency}}</td>
                    <td class="limit_by_day text-left">{{$value->limit_by_day}}</td>
                    <td hidden class="limit_by_week text-left">{{$value->limit_by_week}}</td>
                    <td class="level text-center"><a href="#" class="btn btn-primary btn-xs">{{$value->status}}</a></td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th hidden>Id</th>
                    <th class="text-left">Transaction_type</th>
                    <th class="text-left">Montant min</th>
                    <th class="text-left">Montant max</th>
                    <th class="text-left">Devise</th>
                    <th class="text-left">Limite/Jour</th>
                    <th hidden class="text-left">Limite/Semaine</th>
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
  <div class="modal fade" id="add_limit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Limite des transactions</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('cashier.transaction.limit.post')}}" method="POST">
                @csrf
                <input type="hidden" name="wallet_id" id="e_id" value="">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label" for="transaction_type">Transaction</label>
                        <select name="transaction_type" class="form-select input-air-primary digits" id="transaction_type">
                          <option selected disabled>Choisir un type de transaction</option>
                          <option value="retrait">Retrait</option>
                          <option value="depot">Dépôt</option>
                          <option value="transfert">Transfert</option>
                          {{-- @foreach ($role_name as $role )
                            <option value="{{ $role->role_type }}">{{ $role->role_type }}</option>
                          @endforeach --}}
                        </select>
                        @error('transaction_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="currency">Devise</label>
                        <select name="currency" class="form-select input-air-primary digits" id="currency">
                          <option selected disabled>Choisir une devise</option>
                          <option value="CDF">CDF</option>
                          <option value="USD">USD</option>
                        </select>
                        @error('currency')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                    </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Montant Min.</label>
                    <input class="form-control input-air-primary @error('min_amount') is-invalid @enderror" name="min_amount" id="e_min_amount" type="text" value="" >
                    @error('min_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Montant Max.</label>
                    <input class="form-control input-air-primary @error('max_amount') is-invalid @enderror" name="max_amount" id="e_max_amount" type="text" value="" >
                    @error('max_amount')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label" for="validationCustom01">Nombre Trx/Jour</label>
                    <input class="form-control input-air-primary @error('limit_by_day') is-invalid @enderror" name="limit_by_day" id="e_limit_by_day" type="text" value="" >
                    @error('limit_by_day')
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