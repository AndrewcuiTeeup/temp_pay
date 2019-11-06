@extends('layouts.cms')
@push('custom_css')
    <!-- CSS Plugins-->
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/sc-1.5.0/datatables.min.css"/>
    <link href="{{asset('/template/assets/css/datatable-custom.css')}}" rel="stylesheet">
    <link href="{{asset('/template/assets/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css')}}" rel="stylesheet">
    <!-- /CSS Plugins-->
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-12">
                <h3 class="text-theme my-0">{{__('navigation.order')}}</h3>
            </div>
        </div>
        <!-- End Pgae Title -->

        <!-- Start  Row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Orders</h4>
                        <div class="heading row mb-3">
                            <div class="col-lg-2 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{__('common.status')}}</span>
                                    </div>
                                    {{-- '状态 0-pending 1-成功 2-拒绝 3-初审通过 4- 超时',--}}
                                    <select id="select-filter_status"   class="form-control">
                                        <option value="-1">All</option>
                                        <option value="0">{{__('common.pending')}}</option>
                                        <option value="1">{{__('common.success')}}</option>
                                        <option value="2">{{__('common.rejected')}}</option>
                                        <option value="4">{{__('common.expired')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{__('common.type')}}</span>
                                    </div>
                                    <select id="select-filter_type" name="filter_type"  class="form-control">
                                        <option value="">All</option>
                                        <option value="refID">RefId</option>
                                        <option value="orderId">金桔订单ID</option>
                                        <option value="name">{{__('common.name_person')}}</option>
                                        <option value="amount">支付金额</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 mb-2">
                                <div class="input-group">
                                    <input class="form-control" type="text" id="txtSearch" name="key" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{__('common.creation_time')}}</span>
                                    </div>
                                    <input class="form-control datetimepicker-date"  type="text" id="txtFromDate" name="fromDate" value="" autocomplete="off">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">TO</span>
                                    </div>
                                    <input class="form-control datetimepicker-date" type="text" id="txtToDate" name="toDate" value="" autocomplete="off">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-6 mb-2">
                                <button class="btn btn-primary waves-effect waves-light" type="button" id="btn_search">{{__('buttons.search')}}</button>
                                &nbsp;&nbsp;&nbsp;
                                <button class="btn btn-default waves-effect waves-light" type="button" id="btn_reset">{{__('buttons.reset')}}</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="tableRechargeApplicationLog" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>{{__('common.updated_time')}}</th>
                                    <th>{{__('common.status')}}</th>
                                    <th>金桔订单ID</th>
                                    <th>付款人</th>
                                    <th>入金USD</th>
                                    <th>支付金额</th>
                                    <th>收款银行</th>
                                    <th>SMS ID</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <!-- End  row-->

        <!-- End Page Content -->

    </div>
    <!-- End Container fluid  -->
@endsection
@push('custom_js')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/sc-1.5.0/datatables.min.js"></script>
    <script src="{{asset('/template/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
    {{--<script src="{{asset('/template/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.ja.js')}}"></script>--}}
    <script src="{{asset('/template/assets/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.zh-CN.js')}}"></script>
    <script>
        var datePickerLang='zh-CN';
        $('.datetimepicker-date').datetimepicker({
            language: datePickerLang,
            format: 'yyyy-mm-dd',
            weekStart: 1,
            startView: 2,
            minView: 2,  //Number, String. 默认值：0, 'hour'，日期时间选择器所能够提供的最精确的时间选择视图。
            forceParse: 0
        });
    </script>

    <script>
        $(document).ready(function () {
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
            var tableRechargeApplicationLog=$('#tableRechargeApplicationLog').DataTable( {
                "language": {
                    "url": langUrl
                },
                "processing":true,
                "serverSide": true,
                dom: 'lrtip',
                "aaSorting": [],
                "ajax":{
                    "url": "{{ route("admin.order.ajax") }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                  { "data": "updated_at" },
                  { "data": "status" },
                    { "data": "orderId" },
                    { "data": "payee" },
                    { "data": "amount_usd" },
                    { "data": "final_amount" },
                    { "data": "bank_name" },
                    { "data": "sms_bank_message_id" },
                    { "data": "control" }
                ]
            } );


            //=========
            // search
            //===========
            $('#btn_search').on('click', function () {
                var txtSearch=$('#txtSearch').val();
                var type=$('#select-filter_type').val();
                var status=$('#select-filter_status').val();
                var fromDate=$('#txtFromDate').val();
                var toDate=$('#txtToDate').val();
                var param={"search":txtSearch,"type":type,"status":status,"fromDate":fromDate,"toDate":toDate};
                tableRechargeApplicationLog.search(JSON.stringify(param) ).draw();

            });
            //========
            // reset
            //=========
            $('#btn_reset').on('click', function () {
                $('#txtSearch').val('');
                $('#select-filter_type').val();
                $('#select-filter_status').val('-1')
                $('#txtFromDate').val('');
                $('#txtToDate').val('');
                $('#btn_search').click();
            });

        });


        function rechargeDetail(id)
        {
            var url="{{route('admin.order.detail',':id')}}";
            url = url.replace(':id', id);
            $("#contentBody").load(url,function () {
                $('#editModal').modal('show');
            });
        }
    </script>

@endpush
