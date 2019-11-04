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
                <h3 class="text-theme my-0">{{__('navigation.sms_log') }}</h3>
            </div>
        </div>
        <!-- End Pgae Title -->
        <!-- ============================================================== -->
        <!-- Start Page Content -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div id="divControlMsg" class="alert alert-success" role="alert" style="display: none"></div>
                        <div class="heading row mb-3">
                            <div class="col-lg-2 mb-2">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{__('common.type')}}</span>
                                    </div>
                                    <select id="select-filter_type" name="filter_type"  class="form-control">
                                        <option value="">All</option>
                                        <option value="id">ID</option>
                                        <option value="bank">Bank</option>
                                        <option value="phone">Phone</option>
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
                                        <span class="input-group-text">Time</span>
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
                            <table id="tableDevicesLog" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Status</th>
                                    <th>Time</th>
                                    <th>Message</th>
                                    <th>Bank</th>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>Relate Order</th>
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
        <!-- End row-->

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
            var table=$('#tableDevicesLog').DataTable( {
                "language": {
                    "url": langUrl
                },
                "processing":true,
                "serverSide": true,
                dom: 'lrtip',
                "aaSorting": [],
                "ajax":{
                    "url": "{{ route("admin.setting.sms-log-ajax") }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "id" },
                    { "data": "status" },
                    { "data": "message_time" },
                    { "data": "message" },
                    { "data": "bank" },
                    { "data": "phone" },
                    { "data": "amount" },
                    {"data":"url"}
                                    ]
            } );


        //=========
        // search
        //===========
        $('#btn_search').on('click', function () {
            var txtSearch=$('#txtSearch').val();
            var type=$('#select-filter_type').val();
            var fromDate=$('#txtFromDate').val();
            var toDate=$('#txtToDate').val();
            var param={"search":txtSearch,"type":type,"fromDate":fromDate,"toDate":toDate};
            table.search(JSON.stringify(param) ).draw();

        });
            //========
            // reset
            //=========
            $('#btn_reset').on('click', function () {
                $('#txtSearch').val('');
                $('#select-filter_type').val('');
                $('#txtFromDate').val('');
                $('#txtToDate').val('');
                $('#btn_search').click();
            });

        });
    </script>

@endpush

