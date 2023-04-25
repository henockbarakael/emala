@extends('layouts.master')
@section('content')
@section('title','Compte Client - EMALA')
@section('page','Compte client')
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
                    <div class="card ">
                        <div class="card-body" style="background-image: linear-gradient(to right, #295f12 0%, #34641f 51%, #123304 100%);">                     
                        <div id="demo3"></div>
                        @if($closed == "no")
                        <div class="row g-3 ">
                          <div class="col-md-3">
                            <a href="{{ url('admin/transfert/interne/'.Crypt::encrypt($user_id))}}" class="btn btn-success" id="addDefault">Transfert Interne</a>
                          </div>
                          <div class="col-md-3">
                            <a href="{{ url('admin/retrait/cash/'.Crypt::encrypt($user_id)) }}" class="btn btn-success" id="addToDo">Retrait Interne</a>
                          </div>
                          <div class="col-md-3">
                            <a href="{{ url('admin/depot/cash/'.Crypt::encrypt($user_id)) }}" class="btn btn-success" id="removeBoard">Dépot Par Cash</a>
                          </div>
                          <div class="col-md-3">
                            <a href="{{ url('admin/mobile-money/interne/'.Crypt::encrypt($user_id)) }}" class="btn btn-success" id="removeBoard">Mobile money</a>
                          </div>
                        </div>
                        @else
                          <div class="row g-3 ">
                            <div class="col-md-3">
                              <button  class="btn btn-success" data-bs-toggle="modal" data-original-title="test" data-bs-target="#cash_register" id="addDefault" >Transfert Interne</button>
                            </div>
                            <div class="col-md-3">
                              <button   class="btn btn-success" data-bs-toggle="modal" data-original-title="test" data-bs-target="#cash_register" id="addToDo">Retrait Interne</button>
                            </div>
                            <div class="col-md-3">
                              <button   class="btn btn-success" data-bs-toggle="modal" data-original-title="test" data-bs-target="#cash_register" id="removeBoard">Dépot Par Cash</button>
                            </div>
                            <div class="col-md-3">
                              <button   class="btn btn-success" data-bs-toggle="modal" data-original-title="test" data-bs-target="#cash_register" id="removeBoard">Mobile money</button>
                            </div>
                          </div>
                        @endif
                      </div>
                    </div>
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
                            <div class="col-6 text-md-end border-right">
                              <div class="follow-num counter">{{$acnumber_principal}}</div><span>N° compte principal</span>
                              <h6>USD {{$amount_usd_principal}}</h6>
                              <h6>CDF {{$amount_cdf_principal}}</h6>
                            </div>
                            <div class="col-6 text-md-start">
                              <div class="follow-num counter">{{$acnumber_epargne}}</div><span>N° compte epargne</span>
                              <h6>USD {{$amount_usd_epargne}}</h6>
                              <h6>CDF {{$amount_cdf_epargne}}</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- user profile first-style end-->
                  {{-- <div class="col-sm-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="form theme-form">
                          <div class="row">
                            <div class="col">
                              <div class="mb-3">
                                <label>Project Title</label>
                                <input class="form-control" type="text" placeholder="Project name *">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="mb-3">
                                <label>Client name</label>
                                <input class="form-control" type="text" placeholder="Name client or company name">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="mb-3">
                                <label>Project Rate</label>
                                <input class="form-control" type="text" placeholder="Enter project Rate">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="mb-3">
                                <label>Project Type</label>
                                <select class="form-select">
                                  <option>Hourly</option>
                                  <option>Fix price</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="mb-3">
                                <label>Priority</label>
                                <select class="form-select">
                                  <option>Low</option>
                                  <option>Medium</option>
                                  <option>High</option>
                                  <option>Urgent</option>
                                </select>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-sm-4">
                              <div class="mb-3">
                                <label>Project Size</label>
                                <select class="form-select">
                                  <option>Small</option>
                                  <option>Medium</option>
                                  <option>Big</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="mb-3">
                                <label>Starting date</label>
                                <input class="datepicker-here form-control" type="text" data-language="en">
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="mb-3">
                                <label>Ending date</label>
                                <input class="datepicker-here form-control" type="text" data-language="en">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="mb-3">
                                <label>Enter some Details</label>
                                <textarea class="form-control" id="exampleFormControlTextarea4" rows="3"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="mb-3">
                                <label>Upload project file</label>
                                <form class="dropzone" id="singleFileUpload" action="/upload.php">
                                  <div class="dz-message needsclick"><i class="icon-cloud-up"></i>
                                    <h6>Drop files here or click to upload.</h6><span class="note needsclick">(This is just a demo dropzone. Selected files are <strong>not</strong> actually uploaded.)</span>
                                  </div>
                                </form>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="text-end"><a class="btn btn-success me-3" href="#">Add</a><a class="btn btn-danger" href="#">Cancel</a></div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>
        <!-- Container-fluid Ends-->
      </div>
    </div>


    <div class="modal fade" id="cash_register" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-warning text-warning ml-4"></i> Attention!!!</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Votre caisse est fermée! Veuillez ouvrir votre caisse avant d'effectuer une opération liée à celle-ci, merci!</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>
@endsection