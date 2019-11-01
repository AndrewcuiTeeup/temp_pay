@extends('layouts.cms')
@push('custom_css')
        <!-- CSS Plugins-->
        <link rel="stylesheet" type="text/css"
              href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/sc-1.5.0/datatables.min.css"/>
        <link href="{{asset('/template/assets/css/datatable-custom.css')}}" rel="stylesheet">
        <!-- /CSS Plugins-->
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-12">
                <h3 class="text-theme my-0">{{__('navigation.dashboard')}}</h3>
            </div>
        </div>
        <!-- End Pgae Title -->

        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row total-number">
            <div class="col-lg-3 col-md-6 ">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{__('dashboard.pu_user')}}</h4>
                        <div class="text-right">
                            <h2 class="font-light display-6 mb-0"><i class="mdi mdi-poll text-softblue mr-3"></i>10</h2>
                            <span class="text-muted small">{{__('dashboard.active_num')}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{__('dashboard.merchant_user')}}</h4>
                        <div class="text-right">
                            <h2 class="font-light display-6 mb-0"><i class="mdi mdi-poll text-success mr-3"></i>7</h2>
                            <span class="text-muted small">{{__('dashboard.active_num')}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{__('dashboard.pu_balance')}}</h4>
                        <div class="text-right">
                            <h2 class="font-light display-6 mb-0"><i class="mdi mdi-poll text-purple mr-3"></i>s</h2>
                            <span class="text-muted small">{{__('dashboard.total_balance')}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{__('dashboard.merchant_balance')}}</h4>
                        <div class="text-right">
                            <h2 class="font-light display-6 mb-0"><i class="mdi mdi-poll text-pink mr-3"></i>g</h2>
                            <span class="text-muted small">{{__('dashboard.total_balance')}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End First Row -->



        <!-- End Page Content -->

    </div>
    <!-- End Container fluid  -->
@endsection
@push('custom_js')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/sc-1.5.0/datatables.min.js"></script>
<script>




</script>
@endpush
