@extends('layouts.master')
@section('title', 'Instance Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: Instance Create & View</h6>
        </div>
    </div>
    <div class="hk-pg-header mb-0">
    <div class="d-flex">
            @permission('user.instance.view')
            <form name="instanceForm" action="{{ route('user.instance.create') }}" method="post">
                {{ csrf_field() }}
                <label>
                    <input type="text" class="form-control form-control-sm new" placeholder="instance name" aria-controls="datable_1" name="instance_name" value="">
                </label>
                <button class="btn btn-tool btn-danger" name="save" value="save">Add Instance </button>
            </form>
             @endpermission
        </div>
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
                                <th>Instanse Name </th>
                                <th>token </th>
                                <th>Status </th>
                                <th style="text-align: left;"> Scan </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($instanceDetail as $key=> $instance)
                                <tr>
                                    <td class="serial">{{ $key + $instanceDetail->firstItem()}}</td>
                                    <td> <span class="name">{{ $instance->instance_name }}</span> </td>
                                    <td> <span class="product">{{ $instance->token }}</span> </td>
                                    <td style="font-weight: bold;">
                                        @if($instance->is_status==0)
                                            <span class="badge badge-danger">Not Active</span>
                                        @elseif($instance->is_status==1)
                                           <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td style="text-align: left;">
                                        <button type="submit" class="btn btn-primary" onclick="__appQRScan('{{ Crypt::encryptString($instance->id) }}')">Approve</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"> No Instance in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($instanceDetail->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$instanceDetail->firstItem()}} to {{$instanceDetail->lastItem()}} of {{$instanceDetail->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $instanceDetail->appends(Request::get('qa'))->links() }}
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
    <div class="modal fade" id="scanQRCodeModel">
        <div class="modal-dialog">
            <div class="modal-content bmd-modalContent">

                <div class="modal-body">
          
          <div class="close-button">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
              <div class="embed-responsive embed-responsive-16by9">
                <iframe src="" class="qrCode" title="WhatsappScan"></iframe>
              </div>
                </div>
            </div>
        </div>
    </div>
@endsection
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
                        if(result.success){
                            $('#scanQRCodeModel').modal('show');
                            $('.qrCode').attr('src', result.scan_url);
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
