<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @stack('style')
  <body class="dark-only">
    @include('layouts.loader')
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
      @include('layouts.header')
      <div class="page-body-wrapper">
        @include('layouts.sidebar')
        @yield('content')
        @include('layouts.footer')
      </div>
    </div>
    
    <div class="modal fade" id="cashregister" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
              <div class="text-center mt-4">
                  <h5>Session de caisse <i class="fa fa-warning text-warning"></i></h5>
                  <p class="message" style="font-size: 12px"></p>
              </div>
          </div>
          <div class="modal-btn">
              <form action="" method="POST">
                  @csrf
                  <input type="hidden" name="id" class="e_id" value="">
                  <div class="row">
                      <div class="modal-footer justify-content-center" style="border-top: 0px; margin-top:-10px">
                          <button class="btn btn-primary" type="button" id="btn_continious" onclick="continious()">Continuer</button>
                          <button class="btn btn-secondary" type="button" id="btn_closing" onclick="closing()">Clôturer</button>
                        </div>
                  </div>
              </form>
          </div>
        </div>
      </div>
    </div>
    @yield('script')
  </body>
</html>
