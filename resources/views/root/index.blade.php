@extends('layouts.master')
@section('content')
@section('title','Bienvenue | Emala FinTech')
@section('page','Dashboard')
{!! Toastr::message() !!}
<div class="page-body">
    @include('layouts.page-title')
    <!-- Container-fluid starts-->
    <div class="container-fluid">
      <div class="row">
        <div class="col-xl-3 col-sm-6 box-col-6">
          <div class="card ecommerce-widget">
            <div class="card-body support-ticket-font">
              <div class="row">
                <div class="col-12"><span>Order</span>
                  <h3 class="total-num counter">2563</h3>
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
                <div class="col-12"><span>Pending</span>
                  <h3 class="total-num counter">8943</h3>
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
                <div class="col-12"><span>Running</span>
                  <h3 class="total-num counter">2500</h3>
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
                <div class="col-12"><span>Done</span>
                  <h3 class="total-num counter">5600</h3>
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
      </div>
      <div class="row">
        <div class="col-sm-12 col-xl-12 box-col-12">
          <div class="card">
            <div class="card-body chart-block">
              <div class="chart-overflow" id="column-chart1"></div>
            </div>
          </div>
        </div>
      </div>
      
  </div>
    <!-- Container-fluid Ends-->
  </div>
@endsection