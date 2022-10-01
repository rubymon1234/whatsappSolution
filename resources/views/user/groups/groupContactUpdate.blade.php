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
            <h6 class="hk-pg-title">@yield('title') :: Update Group</h6>
        </div>
    </div>
    @if ($errors->any())
        <label class="control-label" for="inputError" style="color: #dd4b39"><i class="fa fa-times-circle-o" ></i> {{ implode(' | ', $errors->all(':message')) }} .</label>
        <br>
    @endif
    <div class="hk-pg-header mb-0">
        <div class="d-flex">

            @permission('user.instance.view')
            <form name="groupForm" action="{{ route('user.group.update.csv',$group_id) }}" method="post" enctype='multipart/form-data'>
                {{ csrf_field() }}
                <label>
                    <input type="file" class="form-control form-control-sm new" placeholder="upload contacts" aria-controls="datable_1" name="csv_import" value="">
                </label>
                <button class="btn btn-tool btn-danger" name="save" value="save">update Group</button>
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
                                <th>Name </th>
                                <th>Number</th>
                                <th>Email</th>
                                <th>Status </th>
                                <th> Manage </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groupContactList as $key=> $contacts)
                                <tr>
                                    <td class="serial">{{ $key + $groupContactList->firstItem()}}</td>
                                    <td style="font-weight: bold;"><span class="name">{{ $contacts->contact_name }}</span></td>
                                    <td style="font-weight: bold;">
                                        {{$contacts->contact_number}}
                                    </td>
                                    <td >
                                        {{$contacts->contact_email}}
                                    </td>
                                    <td style="font-weight: bold;">
                                        @if($contacts->is_status==0)
                                            <span class="badge badge-danger">Not Active</span>
                                        @elseif($contacts->is_status==1)
                                           <span class="badge badge-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('user.group.contacts.delete' ,Crypt::encrypt($contacts->id)) }}">
                                            <i class="fa fa-trash" data-toggle="tooltip" data-original-title="Edit Chat Instance"></i></a>
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
                    @if($groupContactList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$groupContactList->firstItem()}} to {{$groupContactList->lastItem()}} of {{$groupContactList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $groupContactList->appends(Request::get('qa'))->links() }}
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
@endsection
