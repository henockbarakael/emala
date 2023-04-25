@extends('layouts.master')
@section('content')
@section('title','Bienvenue | Emala FinTech')
@section('page','Dashboard')
{!! Toastr::message() !!}
<div class="page-body">
    {{-- @include('layouts.page-title') --}}
    @include('sweetalert::alert')
    <div class="container-fluid">
      <div class="page-title">
        <div class="row">
          <div class="col-6">
            <h3>Dashboard</h3>
          </div>
          <div class="col-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="index.html"><i data-feather="home"></i></a></li>
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-12 col-xl-12">
          <div class="card">
            <div class="card-body row">
              <div class="col-xl-3 col-sm-6 box-col-6">
                <div class="myshadow p-25 text-center text-white" style="background-color: #28a745">
                  <span>Total Agence</span>
                  <h5 class="m-0 f-18">{{$total_agence}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6">
                <div class="myshadow p-25 text-center text-white" style="background-color: #ffc107">
                  <span>Total User</span>
                  <h5 class="m-0 f-18">{{$total_user}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6">
                <div class="myshadow p-25 text-center text-white" style="background-color: #217ce4">
                  <span>Total Client</span>
                  <h5 class="m-0 f-18">{{$total_client}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6">
                <div class="myshadow p-25 text-center text-white" style="background-color: #dc3545">
                  <span>Total Transaction</span>
                  <h5 class="m-0 f-18">{{$transaction_count}}</h5>
                </div>
              </div>
            
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #9ac927">
                  <span>Dépôt CDF <i class="fa fa-arrow-down" style="color: #217ce4"></i></span>
                  <h5 class="m-0 f-18">{{$depot_cdf}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #9ac927">
                  <span>Dépôt USD <i class="fa fa-arrow-down" style="color: #217ce4"></i></span>
                  <h5 class="m-0 f-18">{{$depot_usd}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #a72983">
                  <span>Retrait CDF <i class="fa fa-arrow-up" style="color: red"></i></span>
                  <h5 class="m-0 f-18">{{$retrait_cdf}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #a72983">
                  <span>Retrait USD <i class="fa fa-arrow-up" style="color: red"></i></span>
                  <h5 class="m-0 f-18">{{$retrait_usd}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #217ce4ad">
                  <span>Revenu CDF <i class="fa fa-arrow-down" style="color: #ffc107"></i></span>
                  <h5 class="m-0 f-18">{{$revenu_cdf}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #217ce4ad">
                  <span>Revenu USD <i class="fa fa-arrow-down" style="color: #ffc107"></i></span>
                  <h5 class="m-0 f-18">{{$revenu_usd}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #ec3ce5">
                  <span>SOLDE AGENCE CDF</span>
                  <h5 class="m-0 f-18">{{$agence_cdf}}</h5>
                </div>
              </div>
              <div class="col-xl-3 col-sm-6 box-col-6 mt-3">
                <div class="myshadow p-25 text-center text-white" style="background-color: #ec3ce5">
                  <span>SOLDE AGENCE USD</span>
                  <h5 class="m-0 f-18">{{$agence_usd}}</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 col-xl-12">
          <div class="card">
            <div class="card-header">
              <h5 style="text-transform: none">Statistique de transactions par jour en CDF</h5>
            </div>
            <div class="card-body">
              <div id="column-chart"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-xl-12">
          <div class="card">
            <div class="card-header">
              <h5 style="text-transform: none">Statistique de transactions par jour en USD</h5>
            </div>
            <div class="card-body">
              <div id="column-chart-usd"></div>
            </div>
          </div>
        </div>
        <div class="col-sm-12 col-xl-6 box-col-6">
          <div class="card">
            <div class="card-header">
              <h5 style="text-transform: none">Pourcentage de transactions du {{$today}}.</h5>
            </div>
            <div class="card-body apex-chart">
              <div id="piechart"></div>
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

    <script src="{{ asset('assets/js/chart/apex-chart/apex-chart.js')}}"></script>
    <script src="{{ asset('assets/js/chart/apex-chart/chart-custom.js')}}"></script>
    <script type="text/javascript">
      // column chart
      var options3 = {
          chart: {
              height: 350,
              type: 'bar',
              toolbar:{
                show: false
              }
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  // endingShape: 'rounded',
                  columnWidth: '55%',
              },
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          series: [{
              name: 'Transfert',
              data: {!! $transfertcdf !!}
          }, {
              name: 'Dépôt',
              data: {!! $depotcdf !!}
          }, {
              name: 'Retrait',
              data: {!! $retraitcdf !!}
          }],
          xaxis: {
              categories: {!! $day !!},
          },
          yaxis: {
              title: {
                  text: 'CDF (Franc congolais)'
              }
          },
          fill: {
              opacity: 1

          },
          tooltip: {
              y: {
                  formatter: function (val) {
                      return "CDF " + val 
                  }
              }
          },
          colors:[ '#28a745' , '#dc3545' , '#ffc107']
      }

      var chart3 = new ApexCharts(
          document.querySelector("#column-chart"),
          options3
      );

      chart3.render();


      // column chart
      var options4 = {
          chart: {
              height: 350,
              type: 'bar',
              toolbar:{
                show: false
              }
          },
          plotOptions: {
              bar: {
                  horizontal: false,
                  // endingShape: 'rounded',
                  columnWidth: '55%',
              },
          },
          dataLabels: {
              enabled: false
          },
          stroke: {
              show: true,
              width: 2,
              colors: ['transparent']
          },
          series: [{
              name: 'Transfert',
              data: {!! $transfertusd !!}
          }, {
              name: 'Dépôt',
              data: {!! $depotusd !!}
          }, {
              name: 'Retrait',
              data: {!! $retraitusd !!}
          }],
          xaxis: {
              categories: {!! $day !!},
          },
          yaxis: {
              title: {
                  text: 'USD (Dollar américain)'
              }
          },
          fill: {
              opacity: 1

          },
          tooltip: {
              y: {
                  formatter: function (val) {
                      return "USD " + val 
                  }
              }
          },
          colors:[ '#28a745' , '#dc3545' , '#ffc107']
      }

      var chart4 = new ApexCharts(
          document.querySelector("#column-chart-usd"),
          options4
      );

      chart4.render();

      // pie chart
      var options8 = {
          chart: {
              width: 380,
              type: 'pie',
          },
          labels: ['Transfert', 'Dépot', 'Retrait'],
          series: [{!! $percent_transfert_count !!} , {!! $percent_depot_count !!}, {!! $percent_retrait_count !!}],
          responsive: [{
              breakpoint: 480,
              options: {
                  chart: {
                      width: 200
                  },
                  legend: {
                      position: 'bottom'
                  }
              }
          }],
          colors:[ '#28a745' , '#dc3545' , '#ffc107', '#a927f9', '#f8d62b']
      }

      var chart8 = new ApexCharts(
          document.querySelector("#piechart"),
          options8
      );

      chart8.render();
    </script>

  @endsection
@endsection