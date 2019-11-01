<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <title>{{ config('app.name') }}</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="format-detection" content="telephone=no">
    <meta name="version" content="{{Config::get('constants.release.version')}}">

    <!-- Favicon -->
    <link rel="icon" href="{{asset('/template/assets/img/favicon.ico')}}">

    <!-- CSS Core-->
    <link href="{{asset('/template/assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('/template/assets/css/blue.css')}}" id="theme" rel="stylesheet">
    <link href="{{asset('/template/assets/css/flag-icon.min.css')}}" rel="stylesheet">

    <!-- CSS Plugins-->
    <!-- Extra js-->
@stack('custom_css')
<!-- /Extra js-->
    <link href="{{asset('/template/assets/css/style.css')}}" rel="stylesheet">
    <!-- ICON SETS -->
    <link href="https://cdn.materialdesignicons.com/3.2.89/css/materialdesignicons.min.css" rel="stylesheet">
</head>

<div id="wrapper">
    @yield('content')
</div>
<!-- JS Core-->
<script src="{{asset('/template/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('/template/assets/js/popper.min.js')}}"></script>
<script src="{{asset('/template/assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('/template/assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{asset('/template/assets/js/waves.js')}}"></script>
<script src="{{asset('/template/assets/js/sidebarmenu.js')}}"></script>
<script src="{{asset('js/bootbox.min.js')}}"></script>

<!-- JS validation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.js"></script>
<!--- local validator  for backup-->
{{--<script src="{{asset('/template/assets/js/validator.js')}}"></script> --}}

<!-- JS Plugins -->
<!--stickey kit -->
<script src="{{asset('/template/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js')}}"></script>

<!--JS Custom -->
<script src="{{asset('/template/assets/js/custom.js')}}"></script>
<!-- Extra js-->
@stack('custom_js')
<!-- /Extra js-->
</body>

</html>