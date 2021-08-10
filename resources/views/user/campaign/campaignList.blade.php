@extends('layouts.master')
@section('title', 'Message Management')
@section('content')
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: Sent</h6>
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
                                <th>Campaign name</th>
                                <th>Plan </th>
                                <th>Instance</th>
                                <th>Type</th>
                                <th>Count</th>
                                <th>Message</th>
                                <th>Sent Time</th>
                                <th>Status</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($campaignList as $key=>$campaign)
                                <tr>
                                    <td class="serial">{{ $key + $campaignList->firstItem()}} </td>
                                    <td> <span class="name">{{ $campaign->campaign_name }}</span> </td>
                                    @php
                                    $planDetail = \App\Helpers\Helper::getPlanDetail(Crypt::encrypt($campaign->current_plan_id));
                                    @endphp
                                     <td> <span class="product">{{ $planDetail->plan_name }}</span> </td>
                                    <td >{{$campaign->instance_name }}</td>
                                    <td >{{ $campaign->type }} </td>
                                    <td>
                                        {{ $campaign->count }}
                                    </td>
                                    <td>
                                        
                                        <?php
                                        $string = strip_tags(rawurldecode($campaign->message));
                                        if (strlen($string) > 10) {

                                            // truncate string
                                            $stringCut = substr($string, 0, 10);
                                            $endPoint = strrpos($stringCut, ' ');
                                            //if the string doesn't contain any space then it will cut without word basis.
                                            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            $string .= '... <i class="fa fa-eye" data-toggle="tooltip" data-original-title="'.rawurldecode($campaign->message).'" ></i>';
                                        }
                                        ?>
                                        <?php  echo $string ?>
                                    </td>
                                    <td>{{ $campaign->start_at }}</td>
                                    <td>
                                        @if($campaign->is_status==0)
                                            <span class="badge badge-warning">Queued</span>
                                        @elseif($campaign->is_status==2)
                                        <span class="badge badge-info">Sending</span>
                                        @elseif($campaign->is_status==1)
                                            <span class="badge badge-success">Sent </span>
                                        @elseif($campaign->is_status==3)
                                            <span class="badge badge-danger">cancelled </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($campaign->is_status !=3)
                                        <a href="javascript:void()">
                                            <i class="fa fa-ban" data-toggle="tooltip" data-original-title="Cancel Campaign" onclick="doubleConfirm('{{ Crypt::encryptString($campaign->id) }}')"></i></a>&nbsp;&nbsp;
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No campaign in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($campaignList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$campaignList->firstItem()}} to {{$campaignList->lastItem()}} of {{$campaignList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $campaignList->appends(Request::get('qa'))->links() }}
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
        function doubleConfirm(campaign_id){
        if (confirm('Are you sure you want to cancel this campaign ?')){
            
            $.ajax(
                {
                    url: '{{ route('ajax.cancel.campaign') }}',
                    dataType: 'json', // what to expect back from the PHP script
                    cache: false,
                    data: { _token: "{{ csrf_token() }}", campaign_id : campaign_id} ,
                    type: 'POST' ,
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
                            //alert(result.response);
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
