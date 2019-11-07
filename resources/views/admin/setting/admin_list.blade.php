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
                <h3 class="text-theme my-0">{{__('navigation.cms_users') }}</h3>
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
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                {{__('buttons.create') }}
                            </button>
                        </p>
                        <div id="divControlMsg" class="alert alert-success" role="alert" style="display: none"></div>
                        <div class="table-responsive">
                            <table id="tableSettingAdminList" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('common.name_person') }}</th>
                                    <th>{{__('common.email') }}</th>
                                    <th>{{__('common.type') }}</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->email}}</td>
                                        <td>
                                            {{-- 1 -管理员 2- 财务 3- sales--}}
                                            @if($row->type==1)
                                                {{__('common.role_admin') }}
                                            @elseif($row->type==2)
                                                {{__('common.role_finance') }}
                                            @elseif($row->type==3)
                                                {{__('common.role_sales') }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="editUser({{$row['id']}})">{{__('common.edit') }}</a>
                                            &nbsp;&nbsp;
                                            @if($row['id']!=$currentUserId)
                                            <a href="javascript:void(0)" onclick="delUser({{$row['id']}},'{{$row['email']}}')">{{__('common.delete') }}</a>
                                                @endif
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

        <!-- End Page Content -->

    </div>
    <!-- End Container fluid  -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{__('buttons.create') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="frmAdd" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="">{{__('common.name_person') }} *</label>
                            <input type="text" class="form-control" name="name" id="" placeholder="" required>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.email') }} *</label>
                            <input type="text" class="form-control" name="email" id="" placeholder="" value="" required email>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.password') }} *</label>
                            <input id="txtPassword" type="password" name="password" placeholder="..." class="form-password form-control" data-minlength="6" data-maxlength="6" maxlength="10" required>
                            <p class="help-block">{{__('common.password_tip') }}</p>
                        </div>
                        <div class="form-group">
                            <label for="">{{__('common.retype_password') }} *</label>
                            <input id="password-confirm" type="password" placeholder="" class="form-control" data-minlength="6" data-maxlength="6" maxlength="10" name="password_confirmation" data-match="#txtPassword" required>
                        </div>

                        <div class="form-group">
                            <label for="">{{__('common.role') }}</label>
                            <select name="type" class="form-control" required>
                         {{--       <option value="">--</option>
                                <option value="3">{{__('common.role_sales') }}</option>
                                <option value="2">{{__('common.role_finance') }}</option>--}}
                                <option value="1">{{__('common.role_admin') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{__('buttons.submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- model edit -->

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">{{__('common.edit') }}</h5>
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
            var tableSettingAdminList = $('#tableSettingAdminList').DataTable({
                "language": {
                    "url": langUrl
                },
                dom: 'lfrtip'
            });
        });
    </script>

    <script>



        function delUser(id,email)
        {
            bootbox.confirm({
                message: "确定删除该账户 ["+email+" ]? ",
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
                        var url = '{{route("admin.setting.admin.delete")}}';
                        $.post(url, {"_token": "{{ csrf_token() }}", id: id}, function (data) {
                            window.location.reload();
                        });
                    }
                }
            });
        }

        function editUser(id)
        {
            var url='{{ route("admin.setting.admin.editForm", ":id") }}';
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
                var url='{{route('admin.setting.admin.add')}}';
                // everything looks good!
                var data=$('#frmAdd').serialize();
                $.ajax({
                    url:url,
                    type:"post",
                    data:data,
                    success:function(data){
                        $('#divControlMsg').html('操作成功').show().delay(1500).fadeOut();
                        $('#myModal').modal('hide')
                        window.location.reload();
                    },
                    error:function(e){
                        alert("错误！！");
                        $('#myModal').modal('hide');
                    }
                });
            }
            return false;
        });

    </script>
@endpush

