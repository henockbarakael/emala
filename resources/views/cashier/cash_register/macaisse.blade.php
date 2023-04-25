@extends('layouts.master')
@section('content')
@section('title','Bienvenue | Emala FinTech')
@section('page','Dashboard')
{{-- {!! Toastr::message() !!} --}}
<div class="page-body">
    {{-- @include('layouts.page-title') --}}
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-6">
            <h3>Dashboard</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href=""><i data-feather="home"></i></a></li>
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      {{-- <div class="row">
        <div class="col-xl-3 col-sm-6 box-col-6">
          <div class="card ecommerce-widget">
            <div class="card-body support-ticket-font">
              <div class="row">
                <div class="col-12"><span>Total Agence</span>
                  <h3 class="total-num counter">{{$brancheTotal}}</h3>
                </div>
              </div>
              <div class="progress-showcase">
                <div class="progress sm-progress-bar">
                  <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 box-col-6">
          <div class="card ecommerce-widget">
            <div class="card-body support-ticket-font">
              <div class="row">
                <div class="col-12"><span>Total User</span>
                  <h3 class="total-num counter">{{$userTotal}}</h3>
                </div>
              </div>
              <div class="progress-showcase">
                <div class="progress sm-progress-bar">
                  <div class="progress-bar bg-secondary" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 box-col-6">
          <div class="card ecommerce-widget">
            <div class="card-body support-ticket-font">
              <div class="row">
                <div class="col-12"><span>Total Client</span>
                  <h3 class="total-num counter">{{$customerTotal}}</h3>
                </div>
              </div>
              <div class="progress-showcase mt-4">
                <div class="progress sm-progress-bar">
                  <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 box-col-6">
          <div class="card ecommerce-widget">
            <div class="card-body support-ticket-font">
              <div class="row">
                <div class="col-12"><span>Total Transaction</span>
                  <h3 class="total-num counter">{{$transactionTotal}}</h3>
                </div>
              </div>
              <div class="progress-showcase mt-4">
                <div class="progress sm-progress-bar">
                  <div class="progress-bar bg-success" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
      <div class="row">
        <div class="col-sm-12">
          <div class="card box-shadow-title">
            {{-- <div class="card-header">
              <h5>Examples</h5><span>While shadows on components are disabled by default in Bootstrap and can be enabled via <code>$enable-shadows</code>, you can also quickly add or remove a shadow with our <code>box-shadow</code> utility classes. Includes support for <code>.shadow-none</code> and three default sizes (which have associated variables to match).</span>
            </div> --}}
            <div class="card-body row">
              <div class="col-12">
                <h6 class="sub-title">Balance des wallets</h6>
              </div>
              <div class="col-sm-3">
                <div class="shadow shadow-showcase p-25 text-center bg-success">
                  <span>Cash Dollars</span>
                  <h5 class="m-0 f-18">{{number_format($tirroir_usdTotal,2)}}</h5>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="shadow shadow-showcase p-25 text-center bg-success">
                  <span>Cash Franc Cd.</span>   
                  <h5 class="m-0 f-18">{{number_format($tirroir_cdfTotal,2)}}</h5>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="shadow shadow-showcase p-25 text-center bg-primary">
                  <span>Virtual Dollars</span>   
                  <h5 class="m-0 f-18">{{number_format($bank_usdTotal,2)}}</h5>
                </div>
              </div>
              <div class="col-sm-3">
                <div class="shadow shadow-showcase p-25 text-center bg-primary">
                  <span>Virtual Franc</span>
                  <h5 class="m-0 f-18">{{number_format($bank_cdfTotal,2)}}</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
            <div class="card">
              <div class="card-header">
                <h5 style="text-transform: none">Historique des transactions</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="display" id="basic-8">
                    <thead>
                      <tr>
                        <th>Expéditeur</th>
                        <th hidden class="text-center">ID Agence</th>
                        <th class="text-center">Montant</th>
                        <th class="text-center">Devise</th>
                        <th class="text-center">Destinataire</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Agence</th>
                        <th>Date de création</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach ($transactions as $key => $value)
                      <tr>
                        <td class="wallet_id"><a href="{{ url('cashier/historique/profil-client/'.Crypt::encrypt($value->sender_phone)) }}">{{$value->sender_phone}}</a></td>
                        <td hidden class="id">{{$value->id}}</td>
                        <td class="balance_cdf text-center">{{$value->amount}}</td>
                        <td class="balance_usd text-center">{{$value->currency}}</td>
                        <td class="type text-center"><a href="{{ url('cashier/historique/profil-client/'.Crypt::encrypt($value->receiver_phone)) }}">{{$value->receiver_phone}}</a></td>
                        <td class="level text-center">{{$value->status}}</td>
                        <td class="level text-center">{{$value->btownship}}</td>
                        <td class="level text-center">{{$value->updated_at}}</td>
                      </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Expéditeur</th>
                        <th hidden class="text-center">ID Agence</th>
                        <th class="text-center">Montant</th>
                        <th class="text-center">Devise</th>
                        <th class="text-center">Destinataire</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Agence</th>
                        <th>Date de création</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </div>

  </div>
    <!-- Container-fluid Ends-->
  </div>
  @section('script')
    @if (count($errors) > 0)
    <script type="text/javascript">
        // $('#open_cash_register').modal('show');
        $( document ).ready(function() {
             $('#open_cash_register').modal('show');
        })
    </script>
    @endif
  @endsection
@endsection