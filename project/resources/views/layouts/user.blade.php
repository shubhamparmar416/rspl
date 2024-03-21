<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{$gs->title}}</title>
    <link rel="shortcut icon" href="{{asset('assets/images/'.$gs->favicon)}}">
    <link href="{{ asset('assets/user/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/user/css/tabler.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/user/css/tabler-flags.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/user/css/tabler-payments.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/user/css/tabler-vendors.min.css')}}" rel="stylesheet"/>
	<link rel="stylesheet" href="{{asset('assets/front/css/toastr.min.css')}}">
    <link href="{{asset('assets/user/css/demo.min.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/user/css/custom.css')}}" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
<style>
	.loader {
  border: 16px solid #f3f3f3; /* Light grey */
  border-top: 16px solid #3498db; /* Blue */
  border-radius: 50%;
  width: 120px;
  height: 120px;
  animation: spin 2s linear infinite;
  z-index: 9999;
  position: fixed;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
   body {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
        }
</style>
    @stack('css')
  </head>
  
  <body >
  <div class="loader"></div>

    <div class="wrapper" style="width: 100%;">
      @includeIf('includes.user.header')

      @includeIf('includes.user.nav')
      <div class="page-wrapper">

        @yield('contents')
        @includeIf('includes.user.footer')
      </div>
    </div>

	<script>
		let mainurl = '{{ url('/') }}';
	</script>
    <script src="{{asset('assets/user/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/user/js/tabler.min.js')}}"></script>
    <script src="{{asset('assets/user/js/demo.min.js')}}"></script>
    <script src="{{asset('assets/front/js/custom.js')}}"></script>
    <script src="{{asset('assets/user/js/notify.min.js')}}"></script>
    <script src="{{asset('assets/front/js/toastr.min.js')}}"></script>
    @stack('js')


<script>
		$('.loader').hide();
	
	'use strict';

	@if(Session::has('message'))
	toastr.options =
	{
		"closeButton" : true,
		"progressBar" : true
	}
		toastr.success("{{ session('message') }}");
	@endif
  
	@if(Session::has('error'))
	toastr.options =
	{
		"closeButton" : true,
		"progressBar" : true
	}
		toastr.error("{{ session('error') }}");
	@endif
  
	@if(Session::has('info'))
	toastr.options =
	{
		"closeButton" : true,
		"progressBar" : true
	}
		toastr.info("{{ session('info') }}");
	@endif
  
	@if(Session::has('warning'))
	toastr.options =
	{
		"closeButton" : true,
		"progressBar" : true
	}
		toastr.warning("{{ session('warning') }}");
	@endif
  </script>
  </body>
</html>