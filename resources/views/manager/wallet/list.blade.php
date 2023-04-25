@extends('layouts.master')
@section('content')
@section('title','Liste de portefeuille')
@section('page','Liste de portefeuille')
{!! Toastr::message() !!}
  <div class="page-body">

    @include('layouts.page-title')
    @include('sweetalert::alert')

    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row mt-2 mb-4">
        <div style="height: 30px">
          <a href="#" class="btn btn-success btn-sm pull-right" title="Ajouter wallet" data-bs-toggle="modal" data-original-title="test" data-bs-target="#add_wallet">Ajouter un wallet</a>
        </div>
      </div>
      <!-- Flexible table width Starts-->
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h5>Wallets</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="display" id="basic-8">
                <thead>
                  <tr>
                    <th>Institution</th>
                    <th hidden>ID</th>
                    <th>Balance</th>
                    <th>Devise</th>
                    <th class="text-center">Recharger</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($wallets as $key => $value)
                  <tr>
                    <td class="bank_name">{{$value->bank_name}}</td>
                    <td hidden class="id">{{$value->id}}</td>
                    <td class="balance">{{$value->balance}}</td>
                    <td class="currency">{{$value->currency}}</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-primary btn-xs WalletTopUp" title="TopUp" data-bs-toggle="modal" data-original-title="test" data-bs-target="#topup_wallet">Top Up</a>
                    </td>
                    <td class="status text-center">{{$value->status}}</td>
                    <td  class="text-center">
                      {{-- <a href="" class="btn btn-success btn-xs userUpdate" title="Edit admin" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit_user"><i class="fa fa-edit"></i></a> --}}
                      <a href="" class="btn btn-danger btn-xs WalletDelete" title="Delete Wallet" data-bs-toggle="modal" data-original-title="test" data-bs-target="#delete_wallet"><i class="fa fa-trash-o"></i></a>
                  </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th>Institution</th>
                    <th hidden>ID</th>
                    <th>Balance</th>
                    <th>Devise</th>
                    <th class="text-center">Recharger</th>
                    <th class="text-center">Status</th>
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
  <div class="modal fade" id="add_wallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Ajouter un wallet</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.wallet.create')}}" method="POST">
                @csrf
                <input type="hidden" name="wallet_id" id="e_id" value="">
                <div class="row g-3">
                  <div class="col-md-12">
                    <label class="form-label" for="institution">Institution</label>
                    <select name="institution" class="form-select input-air-primary digits" id="institution">
                      <option selected disabled>Choisir une institution</option>
                      @foreach ($banks as $banks )
                      <option value="{{ $banks->id }}">{{ $banks->bank_name }}</option>
                      @endforeach
                    </select>
                    @error('institution')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                  </div>
                  {{-- <div class="col-md-6">
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
                    <label class="form-label" for="validationCustom01">Devise de la transaction</label>
                    <select name="currency" class="form-select input-air-primary digits" id="exampleFormControlSelect9">
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
                  </div> --}}
                  
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
  <div class="modal fade" id="topup_wallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel2">Top up wallet</h5>
        </div>
        <div class="modal-body">
            <form action="{{route('admin.wallet.topup')}}" method="POST">
                @csrf
                <input type="hidden" name="wallet_id" id="wallet_id" value="">
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
                  {{-- <div class="col-md-6">
                    <label class="form-label" for="currency">Devise</label>
                    <select name="currency" class="form-select input-air-primary digits" id="e_currency">
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
                  </div> --}}
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
  <div class="modal fade" id="delete_wallet" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="text-center mt-4">
                <h3>Supprimer un wallet</h3>
                <p>Etes-vous sûre de vouloir supprimer?</p>
            </div>
        </div>
        <div class="modal-btn">
            <form action="{{route('admin.wallet.delete')}}" method="POST">
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
        $(document).on('click','.WalletTopUp',function()
        {
            var _this = $(this).parents('tr');
            $('#wallet_id').val(_this.find('.id').text());
            $('#topup_currency').val(_this.find('.currency').text());
        });
    </script>
    {{-- delete js --}}
    <script>
        $(document).on('click','.WalletDelete',function()
        {
            var _this = $(this).parents('tr');
            $('.e_id').val(_this.find('.id').text());
        });
    </script>
    @if (count($errors) > 0)
    <script type="text/javascript">
        $('#topup_wallet').modal('show');
    </script>
    @endif
    @endsection
@endsection