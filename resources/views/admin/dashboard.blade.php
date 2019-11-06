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
            <div class="col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">当天金额</h4>
                        <div class="text-right">
                            <h2 class="font-light display-6 mb-0"><i class="mdi mdi-poll text-success mr-3"></i>CNY {{$today_balance_CNY}} </h2>
                            <span class="text-muted small">入金USD {{$today_balance_USD}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">总金额</h4>
                        <div class="text-right">
                            <h2 class="font-light display-6 mb-0"><i class="mdi mdi-poll text-pink mr-3"></i>CNY {{$balance_CNY}} </h2>
                            <span class="text-muted small">入金USD {{$balance_USD}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{$shop->name}}</h4>
                        <div class="text-left" style="padding-left: 10px;">
                            <h5 class="font-light  mb-0">Code: {{$shop->site_code}} </h5>
                            <h5 class="font-light  mb-0">Secret Key: {{$shop->secret_key}} </h5>
                            <span class="text-muted small">&nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End First Row -->

        <!-- Start Second Row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{__('dashboard.today_withdraw_record')}}</h4>
                        <div id="divControlMsg" class="alert alert-success" role="alert" style="display: none"></div>
                        <div class="table-responsive">
                            <table id="tableTodayResult" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>{{__('common.updated_time')}}</th>
                                    <th>金桔订单ID</th>
                                    <th>付款人</th>
                                    <th>入金USD</th>
                                    <th>支付金额</th>
                                    <th>收款银行</th>
                                    <th>短信ID</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($today_orders as $row)
                                    <?php $viewUrl=route('admin.order.detail',$row->id);?>
                                    <tr>
                                        <td>{{$row->updated_at}}</td>
                                        <td>{{$row->orderId}}</td>
                                        <td>{{$row->payee}}</td>
                                        <td>{{$row->amount_usd}}</td>
                                        <td>{{$row->payment_currency}} {{$row->final_amount}}</td>
                                        <td>{{$row->bank_name}}</td>
                                        <td>{{$row->sms_bank_message_id}}</td>
                                        <td><a href="javascript:void(0)" onclick="commonViewDialog('<?php echo $viewUrl;?>','<?php echo __('common.view')?>')">{{__('common.view')}}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <!-- End Second row-->

        <!-- End Page Content -->

    </div>
    <!-- End Container fluid  -->
@endsection
@push('custom_js')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/sc-1.5.0/datatables.min.js"></script>
    <script>
        //=================
        // load datatable
        //=================
            <?php $_lang=Cookie::get('locale'); ?>

        var langUrl = "{{asset('template/assets/plugins/datatable/i18n/English.json')}}";
        @if($_lang=='zh-CN')
            langUrl = "{{asset('template/assets/plugins/datatable/i18n/Chinese.json')}}";
        @endif
                @if($_lang=='en')
            langUrl = "{{asset('template/assets/plugins/datatable/i18n/English.json')}}";
        @endif
                @if($_lang=='jp')
            langUrl = "{{asset('template/assets/plugins/datatable/i18n/Japanese.json')}}";
        @endif
        //-------------------------
        $(document).ready(function () {
            $('#tableTodayResult').DataTable({
                "language": {
                    "url": langUrl
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        filename: 'recharge_log_<?php echo date('Y-m-d_H-i-s', time())?>',
                        sCharSet: "utf8",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'print',
                        sCharSet: "utf8",
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ]
            });
        });
</script>
@endpush
