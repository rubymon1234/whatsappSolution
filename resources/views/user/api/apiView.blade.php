@extends('layouts.master')
@section('title', 'Api Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List Api Key's</h6>
        </div>
         @permission('api.key.create')
        <a class="btn btn-outline-info pull-right" href="{{ route('api.key.create') }}"> Create Api Key </a>
        @endpermission
    </div>
    <style type="text/css">
        .form-control-sm, .custom-select-sm {
            height: calc(1.8125rem + 11px);
        }
        label{
            margin-bottom: 0px ! important;
        }
    </style>
    <div class="row">
        <div class="col-xl-12">
            <div class="hk-row">
                <div class="table-responsive" style="padding: 1%;">
                    <table class="table table-hover table-bordered mb-0">
                        <thead>
                            <tr>
                                <th >#</th>
                                <th>API Name</th>
                                <th>API Key</th>
                                <th>Instance</th>
                                <th>Status </th>
                                <th>created</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($apiList as $key=> $api)
                                @php
                                   $instance= App\Models\Instance::find($api->instance_id);
                                @endphp
                                <tr>
                                    <td class="serial">{{ $key + $apiList->firstItem()}} </td>
                                    <td> <span class="name">{{ $api->api_name}}</span> </td>
                                    <td> <span class="name">{{ $api->api_key}}</span> </td>
                                    <td> <span class="name">{{ $instance->instance_name}}</span></td>
                                    <td>
                                        @if($api->is_status==0)
                                            <span class="badge badge-danger">Not Active</span>
                                        @elseif($api->is_status==1)
                                           <span class="badge badge-success">Active</span>
                                        @elseif($api->is_status==2)
                                            <span class="badge badge-warning"> Pending</span>
                                        @elseif($api->is_status==3)
                                            <span class="badge badge-danger"> Deleted</span>
                                        @endif
                                    </td>
                                    <td> <span class="name">{{$api->created_at}}</span> </td>

                                    <td style="text-align: left;">
                                        <a href="javascript:void(0)" >
                                            <i class="fa fa-trash" data-toggle="tooltip" data-original-title="Delete API" onclick="doubleConfirm('{{ $api->id }}',3)">
                                            </i>
                                        </a>
                                        
                                        
                                    </td>
                                    {{-- <td> <span class="name"></span> </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7"> No api key in the list...</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($apiList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$apiList->firstItem()}} to {{$apiList->lastItem()}} of {{$apiList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $apiList->appends(Request::get('qa'))->links() }}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
function doubleConfirm(api, status){
    if (confirm('Are you sure you want to continue ?')){
    //alert(api);
        
        $.ajax(
            {
                url: '{{ route('api.block.api') }}',
                dataType: 'json', // what to expect back from the PHP script
                cache: false,
                data: { _token: "{{ csrf_token() }}", api : api , status: status } ,
                type: 'POST',
                beforeSend: function () {
                    $('.preloader-it').show();
                },
                complete: function () {
                    $('.preloader-it').hide();
                },
                success: function (result) {
                    $('#loading').hide();
                    if(result.success){
                        $('.preloader-it').hide();
                       alert(result.response);
                        location.reload();
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
