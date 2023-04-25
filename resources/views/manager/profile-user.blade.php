@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Compte utilisateur')
{!! Toastr::message() !!}

    <div class="page-body">
        
            @include('layouts.page-title')
            @include('sweetalert::alert')
     
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="user-profile">
                <div class="row">
                  <!-- user profile first-style start-->
                  <div class="col-12 ">
                    @if ($role_name == "Manager")
                    <div class="card ">
                      <div class="card-body" style="background-image: linear-gradient(to right, #295f12 0%, #34641f 51%, #123304 100%);">                    
                        <div id="demo3"></div>
                        <div class="row g-3 ">
                          <div class="col-md-3">
                            <a href="{{ url('admin/transfert/interne/'.Crypt::encrypt($user_id))}}" class="btn btn-success" id="addDefault">Approvisionner</a>
                          </div>
                        </div>
                      </div>
                    </div>
                    @endif
                    <div class="col-sm-12">
                      <div class="card hovercard text-center">
                        <div class="cardheader"></div>
                        <div class="user-image">
                          <div class="avatar"><img alt="" src="{{ asset('assets/images/user/7.jpg')}}"></div>
                          <div class="icon-wrapper"><i class="icofont icofont-pencil-alt-5"></i></div>
                        </div>
                        <div class="info">
                          <div class="row">
                            <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="ttl-info text-start">
                                    <h6><i class="fa fa-envelope"></i>   Email</h6><span>{{$email}}</span>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="ttl-info text-start">
                                    <h6><i class="fa fa-calendar"></i>   BOD</h6><span></span>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
                              <div class="user-designation">
                                <div class="title"><a target="_blank" href="">{{$firstname." ".$lastname}}</a></div>
                                <div class="desc">{{$role_name}}</div>
                              </div>
                            </div>
                            <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
                              <div class="row">
                                <div class="col-md-6">
                                  <div class="ttl-info text-start">
                                    <h6><i class="fa fa-phone"></i>   Téléphone</h6><span>{{$phone_number}}</span>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="ttl-info text-start">
                                    <h6><i class="fa fa-location-arrow"></i>   Adresse</h6><span>{{$city}}</span>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <hr>
                          <div class="follow">
                            <div class="row">
                              <div class="col-12 text-center border-right">
                                <div class="follow-num counter">{{$acnumber}}</div>
                                <span>Balance usd : {{$balance_usd}}</span> ||
                                <span>Balance cdf : {{$balance_cdf}}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
        <!-- Container-fluid Ends-->
      </div>
    </div>
@endsection