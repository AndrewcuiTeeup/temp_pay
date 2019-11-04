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
                <h3 class="text-theme my-0">{{__('navigation.bank_setting') }}</h3>
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
                        <p class="col-12">
                            <button type="button" class="btn btn-lg btn-primary" data-toggle="modal" data-target="#myModal">
                                {{__('buttons.create')}}
                            </button>
                        </p>
                        <div id="divControlMsg" class="alert alert-success" role="alert" style="display: none"></div>
                        <div class="table-responsive">
                            <table id="tableSettingBankList" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('common.bank_account')}}</th>
                                    <th>{{__('common.bank_cardholder')}}</th>
                                    <th>{{__('common.bank_name')}}</th>
                                    <th>{{__('common.bank_branch')}}</th>


                                    <th>{{__('common.status')}}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->bank_account}}</td>
                                        <td>{{$row->bank_cardholder}}</td>
                                        <td>{{$row->bank_name}}</td>
                                        <td>{{$row->bank_branch}}</td>
                                        <td>
                                            {{-- 状态 是否激活 1- 已经激活 0-关闭 -1 - 已删除 --}}
                                            @if($row->status==1){{__('common.activated')}}@endif
                                            @if($row->status==0)<span class="text-danger">{{__('common.suspended')}}</span> @endif
                                        </td>
                                        <td>
                                            @if($row->status==1)
                                                <a href="javascript:void(0)" onclick="suspendBank({{$row->id}})"
                                                   class="btn btn-sm btn-danger">{{__('common.suspend')}}</a>
                                            @endif
                                            @if($row->status==0)
                                                <a href="javascript:void(0)" onclick="activateBank({{$row->id}})"
                                                   class="btn btn-sm btn-success">{{__('common.activate')}}</a>
                                            @endif
                                                &nbsp;&nbsp;
                                            <a href="javascript:void(0)" onclick="editBank({{$row['id']}})">{{__('common.edit')}}</a>
                                            &nbsp;&nbsp;
                                            <a href="javascript:void(0)" onclick="delBank({{$row['id']}})">{{__('common.delete')}}</a>


                                        </td>
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
        <!-- End row-->

        <!-- start row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <p class="col-12">
                            <h3 class="card-title">SMS 银行模板</h3>
                        </p>
                        <div class="table-responsive">
                            <table  class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('common.bank_account')}}</th>
                                    <th>查找开始点</th>
                                    <th>查找结束点</th>
                                    <th>例子</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($template as $row)
                                    <tr>
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->bank_name}}</td>
                                        <td>{{$row->check_word_start}}</td>
                                        <td>{{$row->check_word_end}}</td>
                                        <td>{{$row->msg_example}}</td>
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
        <!-- End row-->

        <!-- End Page Content -->

    </div>
    <!-- End Container fluid  -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{__('buttons.create')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmAdd">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">{{__('common.bank_name')}} *</label>
                            <select class="form-control" name="bank_name" id="" placeholder="" required>>
                                <option value="">-</option>
                                @foreach($availableBank as $val)
                                    <option value="{{$val}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.bank_branch')}}</label>
                            <input type="text" class="form-control" name="bank_branch" id="" placeholder="" value="" >
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.bank_account')}} *</label>
                            <input type="text" class="form-control" name="bank_account" id="" placeholder="" value=""  required>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.bank_cardholder')}} *</label>
                            <input type="text" class="form-control" name="bank_cardholder" id="" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label for="">QR Code * </label>
                            <p>
                                <img class="img-qrcode" src="" height="80px">
                            </p>
                            <input type="text" class="form-control link-qrcode" name="qrcode" id="" placeholder="" value=""  required>
                            <input type="file" name="image" onchange="encodeImagetoBase64(this)">
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.status')}}</label>
                            <select name="status" class="form-control" required>
                                <option value="1">{{__('common.activate')}}</option>
                                <option value="0">{{__('common.suspend')}}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('buttons.submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- model edit -->

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{__('common.edit')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentBody">
                </div>
            </div>
        </div>
    </div>


@endsection

@push('custom_js')
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.4/b-colvis-1.5.4/b-html5-1.5.4/b-print-1.5.4/sc-1.5.0/datatables.min.js"></script>


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
            var tableSettingBankList = $('#tableSettingBankList').DataTable({
                "language": {
                    "url": langUrl
                },
                dom: 'lfrtip'
            });
        });
    </script>

    <script>

        function activateBank(id) {
            bootbox.confirm({
                message: "{{__('common.dl_msg_active_it')}}",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = '{{route('admin.setting.bank-status')}}';
                        $.post(url, {"_token": "{{ csrf_token() }}", id: id, status: 1}, function (data) {
                            window.location.reload();
                        });
                    }
                }
            });
        }

        //suspend account
        function suspendBank(id) {
            bootbox.confirm({
                message: "{{__('common.dl_msg_suspend_it')}}?",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        var url = '{{route('admin.setting.bank-status')}}';
                        $.post(url, {"_token": "{{ csrf_token() }}", id: id, status: 0}, function (data) {
                            window.location.reload();
                        });
                    }
                }
            });
        }

        function delBank(id)
        {
            bootbox.confirm({
                message: "{{__('common.dl_msg_delete_it')}}",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if(result) {
                        var url = '{{route("admin.setting.bank-status")}}';
                        $.post(url, {"_token": "{{ csrf_token() }}", id: id, status: -1}, function (data) {
                            window.location.reload();
                        });
                    }
                }
            });
        }

        function editBank(id)
        {
            var url='{{ route("admin.setting.bank.editForm", ":id") }}';
            url = url.replace(':id', id);
            $("#contentBody").load(url,function () {
                $('#editModal').modal('show');
            });
        }

        $('#myModal').on('shown.bs.modal', function () {
            $('#frmAdd').trigger("reset");
        });
        // 新增
        $('#frmAdd').validator().on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                var url='{{route('admin.setting.bank.add')}}';
                // everything looks good!
                var data=$('#frmAdd').serialize();
                $.ajax({
                    url:url,
                    type:"post",
                    data:data,
                    success:function(data){
                        $('#divControlMsg').html("{{__('common.success')}}").show().delay(1500).fadeOut();
                        $('#myModal').modal('hide');
                        window.location.reload();
                    },
                    error:function(e){
                        alert("error！！");
                        $('#myModal').modal('hide');
                    }
                });
            }
            return false;
        });


        function encodeImagetoBase64(element) {
            var file = element.files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                $(".link-qrcode").val(reader.result);
                $(".img-qrcode").attr("src",reader.result);

            }

            reader.readAsDataURL(file);

        }
    </script>
@endpush

