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
    <link href="{{asset('/template/assets/css/style.css')}}?v={{Config::get('constants.release.version')}}" rel="stylesheet">
    <!-- ICON SETS -->
    <link href="https://cdn.materialdesignicons.com/3.2.89/css/materialdesignicons.min.css" rel="stylesheet">
</head>

<body class="fix-header fix-sidebar card-no-border">
<?php $level=Auth::guard('admin')->user()->type; ?>
<div id="main-wrapper">
    <!-- ============================================================== -->
    <!-- Topbar header  -->
    <!-- ============================================================== -->
    <header class="topbar">
        <nav class="navbar top-navbar navbar-expand-md navbar-light">
            <!-- Logo icon -->
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <!-- Logo icon -->
                    <div class="div-logo">

                    </div>
                </a>
            </div>
            <!-- End Logo -->
            <div class="navbar-collapse">
                <ul class="navbar-nav mr-auto mt-md-0">
                    <li class="nav-item"><a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a></li>
                </ul>
                <!-- User / Language -->
                <ul class="navbar-nav my-lg-0">
                    <li class="nav-item hidden-sm">
                        <a class="nav-link">{{auth()->user()->email}}</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php $_lang=Cookie::get('locale'); ?>
                            @switch($_lang)
                                @case('en')
                                <i class="flag-icon flag-icon-gb mr-2"></i>
                                @break
                                @case('zh-CN')
                                <i class="flag-icon flag-icon-cn mr-2"></i>
                                @break
                                @case('jp')
                                <i class="flag-icon flag-icon-jp mr-2"></i>
                                @break
                                @default
                                <i class="flag-icon flag-icon-gb"></i>
                                <?php $_lang='en'; ?>
                            @endswitch
                        </a>
                        <div class="dropdown-menu  dropdown-menu-right">
                            @if($_lang!='en')
                                <a class="dropdown-item lang-change" href="{{ url('admin/lang/en')}}" data-lang="en"><i class="flag-icon flag-icon-gb mr-2"></i>English</a>
                            @endif
                            @if($_lang!='zh-CN')
                                <a class="dropdown-item lang-change"   href="{{ url('admin/lang/zh-CN') }}" data-lang="cn"><i class="flag-icon flag-icon-cn mr-2"></i>简体中文</a>
                            @endif
                            @if($_lang!='jp')
                                <a class="dropdown-item lang-change"   href="{{ url('admin/lang/jp') }}" data-lang="jp"><i class="flag-icon flag-icon-jp mr-2"></i>日本語</a>
                            @endif
                        </div>
                    </li>
                    <li class="divider"></li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link"><span class="hidden-sm">{{__('buttons.sign_out')}}</span><span class="d-inlineblock d-md-none"><i class="mdi mdi-power-standby"></i></span></a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- End Topbar header -->

    <!-- ============================================================== -->
    <!-- Left Sidebar -->
    <!-- ============================================================== -->
    <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
            <!-- Sidebar navigation-->
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li>
                        <a href="{{route('admin.dashboard')}}"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">{{__('navigation.dashboard')}}</span></a>
                    </li>
                    <li>
                        <a href="{{route('admin.order.list')}}"><i class="mdi mdi-cash"></i><span class="hide-menu">{{__('navigation.order')}}</span></a>
                    </li>
                    <li>
                        <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu">{{__('navigation.system_setting')}}</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{route('admin.setting.bank-list')}}"><i class="mdi mdi-credit-card"></i><span class="hide-menu">{{__('navigation.bank_setting')}}</span></a></li>
                            <li><a href="{{route('admin.setting.sms-log')}}"><i class="mdi mdi-cellphone-message"></i><span class="hide-menu">{{__('navigation.sms_log')}}</span></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
    </aside>
<!-- End Left Sidebar  -->

    <div class="page-wrapper">
        <!-- Content -->
    @yield('content')
    <!-- /Content -->

        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            <div class="float-right">© </div>
            <div class="clearfix"></div>
        </footer>
        <!-- End footer -->
        <!-- common view model -->
        <div class="modal fade" id="commonViewModal" tabindex="-1" role="dialog" aria-labelledby="commonViewModalModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commonViewModalTitle"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="commonViewContentBody">
                    </div>
                </div>
            </div>
        </div>
        <!-- / model -->
    </div>
    <!-- End Page wrapper  -->
</div>
<!-- End Wrapper -->




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

<script>

    $(document).ready(function() {
        $('.btn-submit-waiting').on('click', function() {
            var $this = $(this);
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            if ($(this).html() !== loadingText) {
                $this.data('original-text', $(this).html());
                $this.html(loadingText);
            }
            setTimeout(function() {
                $this.html($this.data('original-text'));
            }, 5000);
        });
    });

    function commonViewDialog(url,title)
    {
        $("#commonViewContentBody").load(url,function () {
            $('#commonViewModalTitle').html(title);
            $('#commonViewModal').modal('show');
        });
    }
</script>
<!--JS Custom -->
<script src="{{asset('/template/assets/js/custom.js')}}"></script>
<!-- Extra js-->
@stack('custom_js')
<!-- /Extra js-->
</body>

</html>