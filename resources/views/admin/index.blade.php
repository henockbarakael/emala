@extends('layouts.master')
@section('content')
@section('title','Bienvenue | Emala FinTech')
@section('page','Dashboard')
{!! Toastr::message() !!}
<div class="post d-flex flex-column-fluid" id="kt_post">
  <!--begin::Container-->
  <div id="kt_content_container" class="container-xxl">
      <!--begin::Row-->
      <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
                      <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: #2196f3;background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">All Charge</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href=""  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$debit_success}} Successful</span>
                              <span>{{$percent_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_failed}} Failed</span>
                              <span>{{$percent_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_pending}} Pending</span>
                              <span>{{$percent_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_submitted}} Submitted</span>
                              <span>{{$percent_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: #2196f3;background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">All Payout</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.all.payout')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$credit_success}} Successful</span>
                              <span>{{$percent_success_payout}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_failed}} Failed</span>
                              <span>{{$percent_failed_payout}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_pending}} Pending</span>
                              <span>{{$percent_pending_payout}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_submitted}} Submitted</span>
                              <span>{{$percent_submitted_payout}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <!--begin::Col-->
          <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: #DB1430;background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">Airtel Charge</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.airtel.charge')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$debit_airtel_success}} Successful</span>
                              <span>{{$p_adebit_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_airtel_failed}} Failed</span>
                              <span>{{$p_adebit_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_airtel_pending}} Pending</span>
                              <span>{{$p_adebit_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_airtel_submitted}} Submitted</span>
                              <span>{{$p_adebit_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: #DB1430;background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">Airtel Payout</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.airtel.payout')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$credit_airtel_success}} Successful</span>
                              <span>{{$p_acredit_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_airtel_failed}} Failed</span>
                              <span>{{$p_acredit_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_airtel_pending}} Pending</span>
                              <span>{{$p_acredit_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_airtel_submitted}} Submitted</span>
                              <span>{{$p_acredit_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: #119e3e;background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">Vodacom Charge</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.vodacom.charge')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$debit_vodacom_success}} Successful</span>
                              <span>{{$p_vdebit_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_vodacom_failed}} Failed</span>
                              <span>{{$p_vdebit_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_vodacom_pending}} Pending</span>
                              <span>{{$p_vdebit_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_vodacom_submitted}} Submitted</span>
                              <span>{{$p_vdebit_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: #119e3e;background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">Vodacom Payout</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.vodacom.payout')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$credit_vodacom_success}} Successful</span>
                              <span>{{$p_vcredit_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_vodacom_failed}} Failed</span>
                              <span>{{$p_vcredit_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_vodacom_pending}} Pending</span>
                              <span>{{$p_vcredit_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_vodacom_submitted}} Submitted</span>
                              <span>{{$p_vcredit_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: rgb(255, 133, 27);background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">Orange Charge</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.orange.charge')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$debit_orange_success}} Successful</span>
                              <span>{{$p_odebit_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_orange_failed}} Failed</span>
                              <span>{{$p_odebit_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_orange_pending}} Pending</span>
                              <span>{{$p_odebit_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$debit_orange_submitted}} Submitted</span>
                              <span>{{$p_odebit_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
          <!--begin::Col-->
          <div class="col-lg-6 col-xxl-4">
              <!--begin::Card widget 20-->
              <div class="card card-flush myshadow" style="background-color: rgb(255, 133, 27);background-image:url('assets/media/patterns/vector-1.png')">
                  <!--begin::Header-->
                  <div class="card-header pt-5">
                      <!--begin::Title-->
                      <div class="card-title d-flex flex-column">
                          <!--begin::Amount-->
                          <span class="fw-bold text-white me-2 lh-1" style="font-size: 20px">Orange Payout</span>
                          <!--end::Amount-->
                          <!--begin::Subtitle-->
                          <span class="text-white opacity-75 pt-1 fw-semibold fs-6"><a href="{{route('supportone.transaction.orange.payout')}}"  class="text-white">Details<i class="fa fa-info-circle" style="color: white; margin-left: 5px"></i></a></span>
                          <!--end::Subtitle-->
                      </div>
                      <!--end::Title-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Card body-->
                  <div class="card-body d-flex align-items-end pt-0">
                      <!--begin::Progress-->
                      <div class="d-flex align-items-center flex-column mt-3 w-100">
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                              <span>{{$credit_orange_success}} Successful</span>
                              <span>{{$p_ocredit_success}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_orange_failed}} Failed</span>
                              <span>{{$p_ocredit_failed}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_orange_pending}} Pending</span>
                              <span>{{$p_ocredit_pending}}%</span>
                          </div>
                          
                          <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-2 mb-2">
                              <span>{{$credit_airtel_submitted}} Submitted</span>
                              <span>{{$p_ocredit_submitted}}%</span>
                          </div>
                          
                      </div>
                      <!--end::Progress-->
                  </div>
                  <!--end::Card body-->
              </div>
              <!--end::Card widget 20-->
          </div>
      </div>

      <div class="row g-5 g-xl-8">
          <div class="col-xl-12">
              <!--begin::Charts Widget 1-->
              <div class="card card-xl-stretch mb-xl-8 myshadow">
                  <!--begin::Header-->
                  <div class="card-header border-0 pt-5">
                      <!--begin::Title-->
                      <h3 class="card-title align-items-start flex-column">
                          <span class="card-label fw-bold fs-3 mb-1">Recent Statistics</span>
                          <span class="text-muted fw-semibold fs-7">More than 400 new members</span>
                      </h3>
                      <!--end::Title-->
                      <!--begin::Toolbar-->
                      <div class="card-toolbar">
                          <!--begin::Menu-->
                          <button type="button" class="btn btn-sm btn-icon btn-color-primary btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                              <!--begin::Svg Icon | path: icons/duotune/general/gen024.svg-->
                              <span class="svg-icon svg-icon-2">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewbox="0 0 24 24">
                                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                          <rect x="5" y="5" width="5" height="5" rx="1" fill="currentColor"></rect>
                                          <rect x="14" y="5" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                                          <rect x="5" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                                          <rect x="14" y="14" width="5" height="5" rx="1" fill="currentColor" opacity="0.3"></rect>
                                      </g>
                                  </svg>
                              </span>
                              <!--end::Svg Icon-->
                          </button>
                          <!--begin::Menu 1-->
                          <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true" id="kt_menu_634da2a99e677">
                              <!--begin::Header-->
                              <div class="px-7 py-5">
                                  <div class="fs-5 text-dark fw-bold">Filter Options</div>
                              </div>
                              <!--end::Header-->
                              <!--begin::Menu separator-->
                              <div class="separator border-gray-200"></div>
                              <!--end::Menu separator-->
                              <!--begin::Form-->
                              <div class="px-7 py-5">
                                  <!--begin::Input group-->
                                  <div class="mb-10">
                                      <!--begin::Label-->
                                      <label class="form-label fw-semibold">Status:</label>
                                      <!--end::Label-->
                                      <!--begin::Input-->
                                      <div>
                                          <select class="form-select form-select-solid" data-kt-select2="true" data-placeholder="Select option" data-dropdown-parent="#kt_menu_634da2a99e677" data-allow-clear="true">
                                              <option></option>
                                              <option value="1">Approved</option>
                                              <option value="2">Pending</option>
                                              <option value="2">In Process</option>
                                              <option value="2">Rejected</option>
                                          </select>
                                      </div>
                                      <!--end::Input-->
                                  </div>
                                  <!--end::Input group-->
                                  <!--begin::Input group-->
                                  <div class="mb-10">
                                      <!--begin::Label-->
                                      <label class="form-label fw-semibold">Member Type:</label>
                                      <!--end::Label-->
                                      <!--begin::Options-->
                                      <div class="d-flex">
                                          <!--begin::Options-->
                                          <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                                              <input class="form-check-input" type="checkbox" value="1">
                                              <span class="form-check-label">Author</span>
                                          </label>
                                          <!--end::Options-->
                                          <!--begin::Options-->
                                          <label class="form-check form-check-sm form-check-custom form-check-solid">
                                              <input class="form-check-input" type="checkbox" value="2" checked="checked">
                                              <span class="form-check-label">Customer</span>
                                          </label>
                                          <!--end::Options-->
                                      </div>
                                      <!--end::Options-->
                                  </div>
                                  <!--end::Input group-->
                                  <!--begin::Input group-->
                                  <div class="mb-10">
                                      <!--begin::Label-->
                                      <label class="form-label fw-semibold">Notifications:</label>
                                      <!--end::Label-->
                                      <!--begin::Switch-->
                                      <div class="form-check form-switch form-switch-sm form-check-custom form-check-solid">
                                          <input class="form-check-input" type="checkbox" value="" name="notifications" checked="checked">
                                          <label class="form-check-label">Enabled</label>
                                      </div>
                                      <!--end::Switch-->
                                  </div>
                                  <!--end::Input group-->
                                  <!--begin::Actions-->
                                  <div class="d-flex justify-content-end">
                                      <button type="reset" class="btn btn-sm btn-light btn-active-light-primary me-2" data-kt-menu-dismiss="true">Reset</button>
                                      <button type="submit" class="btn btn-sm btn-primary" data-kt-menu-dismiss="true">Apply</button>
                                  </div>
                                  <!--end::Actions-->
                              </div>
                              <!--end::Form-->
                          </div>
                          <!--end::Menu 1-->
                          <!--end::Menu-->
                      </div>
                      <!--end::Toolbar-->
                  </div>
                  <!--end::Header-->
                  <!--begin::Body-->
                  <div class="card-body">
                      <!--begin::Chart-->
                      <div id="kt_charts_widget_1_chart" style="height: 350px"></div>
                      <!--end::Chart-->
                  </div>
                  <!--end::Body-->
              </div>
              <!--end::Charts Widget 1-->
          </div>
      </div>


      <!--end::Row-->
  </div>
  <!--end::Container-->
</div>
@endsection