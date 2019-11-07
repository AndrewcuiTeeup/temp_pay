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
                <h3 class="text-theme my-0">{{__('navigation.device_setting')}}</h3>
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
                        <div class="table-responsive">
                            <table id="tableDevices" class="table nowrap" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>{{__('common.name_object')}}</th>
                                    <th>{{__('common.email')}}</th>
                                    <th>{{__('common.status')}}</th>
                                    <th>{{__('common.updated_time')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{$row->id}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->notice_email}}</td>
                                        <td>@if($row->is_online)  <span class="badge badge-success">On</span> @else<span class="badge badge-danger">Off</span>@endif </td>
                                        <td>{{$row->updated_at}}</td>
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table  class="table table-striped table-head-bg-default  mt-3" style="width:100%">
                                <thead>
                                <tr>
                                    <th>{{__('common.name_object')}}</th>
                                    <th>{{__('common.description')}}</th>
                                    <th>{{__('common.value')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($settingOptions as $row)
                                    <tr>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->description}}</td>
                                        <td>{{$row->value}}</td>
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

@endsection

@push('custom_js')


@endpush

