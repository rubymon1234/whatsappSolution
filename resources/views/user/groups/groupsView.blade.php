@extends('layouts.master')
@section('title', 'Group Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <style type="text/css">
        .form-control-sm, .custom-select-sm {
            height: calc(1.8125rem + 11px);
        }
        label {
            margin-bottom: 0px ! important;
        }
    </style>
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Group</h6>
        </div>
    </div>
    @if ($errors->any())
        <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
        <br>
    @endif
    <div class="hk-pg-header mb-0">
        <div class="d-flex">

            @permission('user.instance.view')
            <form name="groupForm" action="{{ route('user.group.create') }}" method="post" enctype='multipart/form-data'>
                {{ csrf_field() }}
                <label>
                    <input type="text" class="form-control form-control-sm new" placeholder="group name" aria-controls="datable_1" name="group_name" value="">
                </label>
                <label>
                    <input type="file" class="form-control form-control-sm new" placeholder="upload contacts" aria-controls="datable_1" name="csv_import" value="">
                </label>
                <button class="btn btn-tool btn-danger" name="save" value="save">Create Group</button>
                <button class="btn btn-tool btn-info" name="save" value="download">Demo CSV</button>
            </form>
             @endpermission
            </div>
        </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="table-responsive" style="padding: 1%;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th >#</th>
                                <th>Group name </th>
                                <th>Group Contacts(count) </th>
                                <th>Status </th>
                                <th>Created date </th>
                                <th> Manage </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupList as $key=> $group)
                                <tr>
                                    <td class="serial">{{ $key + $groupList->firstItem()}}</td>
                                    <td><span class="name">{{ $group->group_name }}</span></td>
                                    @php
                                    $groupCount = \App\Helpers\Helper::getGroupContactCount($group->id);
                                    @endphp
                                    <td style="font-weight: bold;"><span class="badge badge-info">{{$groupCount}}

                                    </span></td>
                                    <td style="font-weight: bold;">
                                        @if($group->is_status==0)
                                            <span class="badge badge-danger">Not Active</span>
                                        @elseif($group->is_status==1)
                                           <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>{{ $group->created_at }}</td>
                                    <td>
                                        <a href="{{ route('user.group.update' ,Crypt::encrypt($group->id)) }}">
                                            <i class="fa fa-edit" data-toggle="tooltip" data-original-title="Edit group contacts"></i></a>
                                            &nbsp;&nbsp;
                                            <a href="{{ route('user.group.group.delete' ,Crypt::encrypt($group->id)) }}">
                                            <i class="fa fa-trash" data-toggle="tooltip" data-original-title="Delete group with contacts"></i></a>
                                            &nbsp;&nbsp;
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"> No Groups in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($groupList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$groupList->firstItem()}} to {{$groupList->lastItem()}} of {{$groupList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $groupList->appends(Request::get('qa'))->links() }}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- jQuery -->
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script type="text/javascript">
    function __appQRScan(instance_id){
        if(typeof  instance_id !=='undefined' && instance_id !=''){
            $.ajax(
                {
                    url: '{{ route('user.instance.postqrscan') }}',
                    dataType: 'json', // what to expect back from the PHP script
                    cache: false,
                    data: { _token: "{{ csrf_token() }}", instance_id : instance_id } ,
                    type: 'POST' ,
                    beforeSend: function () {
                        $('.preloader-it').show();
                    },
                    complete: function () {
                        $('.preloader-it').hide();
                    },
                    success: function (result) {
                        $('.preloader-it').hide();
                        console.log(result);
                        if(result.success){
                            $('#scanQRCodeModel').modal('show');
                            $('.qrCode').attr('src', result.scan_url);
                        }else{
                            console.log("Something went wrong in server!!");
                        }
                    },
                    error: function (response) {
                        console.log('Server error');
                    }
                }
            );
        }
    }
</script>
@endsection
