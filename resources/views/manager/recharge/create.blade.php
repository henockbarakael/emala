@extends('layouts.master')
@section('content')
@section('title','Demande d\'approvisionnement')
@section('page','Demande d\'approvisionnement')
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
                    <th>Caissier</th>
                    <th class="text-left">Montant</th>
                    {{-- <th class="text-center">Devise</th> --}}
                    {{-- <th class="text-center">Objet</th> --}}
                    <th class="text-center">Status</th>
                    <th class="text-left">Date création</th>
                    <th class="text-left">Date réponse</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                @foreach ($tickets as $key => $value)
                  <tr>
                    <td hidden class="id">{{$value->id}}</td>
                    <td class="requestId">{{$value->request_id}}</td>
                    <td class="brancheId">{{$value->firstname." ".$value->lastname}}</td>
                    <td class="amount text-left">{{$value->amount." ".$value->currency}}</td>
                    {{-- <td class="currency text-center">{{$value->currency}}</td> --}}
                    {{-- <td class="account_level text-center">{{$value->subject}}</td> --}}
                    <td class="text-center status">
                        @if ($value->status == "En attente")
                        <span class="badge badge-warning">{{$value->status}}</span>
                        @elseif ($value->status == "Approuver")
                        <span class="badge badge-success">{{$value->status}}</span>
                        @endif
                        
                    </td>
                    <td class="created_at text-left">{{$value->created_at}}</td>
                    <td class="updated_at text-left">{{$value->updated_at}}</td>
                    <td class="text-center">
                        <a href="" class="btn btn-primary btn-xs requestSuccess"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#success"><i class="fa fa-check-square"></i></a>
                        <a href="" class="btn btn-secondary btn-xs requestFailed"  data-bs-toggle="modal" data-original-title="test" data-bs-target="#failed"><i class="fa fa-minus-circle"></i></a>
                    </td>
                  </tr>
                @endforeach
                </tbody>
                <tfoot>
                  <tr>
                    <th hidden>#</th>
                    <th>N° Ticket</th>
                    <th>Caissier</th>
                    <th class="text-left">Montant</th>
                    {{-- <th class="text-center">Devise</th> --}}
                    {{-- <th class="text-center">Objet</th> --}}
                    <th class="text-center">Status</th>
                    <th class="text-left">Date création</th>
                    <th class="text-left">Date réponse</th>
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

  <div class="modal fade" id="failed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="text-center mt-4">
                <h3>Rejéter la demande</h3>
                <p>Etes-vous sûre de rejeter la demande?</p>
            </div>
        </div>
        <div class="modal-btn">
            <form action="{{route('manager.reharge.request.failed')}}" method="POST">
                @csrf
                <input type="hidden" name="id" class="e_id" value="">
                <div class="row">
                    <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-secondary" type="submit">Oui</button>
                      </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="success" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
            <div class="text-center mt-4">
                <h3>Accepter la demande</h3>
                <p>Etes-vous sûre d'autoriser ce recharge?</p>
            </div>
        </div>
        <div class="modal-btn">
            <form action="{{route('manager.reharge.request.success')}}" method="POST">
                @csrf
                <input type="hidden" name="id" class="s_id" value="">
                {{-- <input type="hidden" name="user_id" id="s_user_id" value="">
                <input type="hidden" name="branche_id" id="s_branche_id" value="">
                <input type="hidden" name="amount" id="s_amount" value="">
                <input type="hidden" name="currency" id="s_currency" value=""> --}}
                <div class="row">
                    <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Annuler</button>
                        <button class="btn btn-success" type="submit">Oui</button>
                      </div>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>

  @section('script')

    <script>
        $(document).on('click','.requestSuccess',function()
        {
            var _this = $(this).parents('tr');
            $('.s_id').val(_this.find('.id').text());
            // $('.s_user_id').val(_this.find('.requestId').text());
            // $('.s_branche_id').val(_this.find('.brancheId').text());
            // $('.s_amount').val(_this.find('.amount').text());
            // $('.s_currency').val(_this.find('.currency').text());
        });
    </script>
    <script>
      $(document).on('click','.requestFailed',function()
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