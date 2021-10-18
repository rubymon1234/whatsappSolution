@extends('layouts.master')
@section('title', 'Interactive Bot')
@section('content')
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<div class="container-fluid mt-xl-50 mt-sm-30 mt-15">
   <!-- Row >>  -->
    @include('errors.status')
    <div class="hk-pg-header mb-10">
        <div>
            <h6 class="hk-pg-title">@yield('title') :: List</h6>
        </div>
    </div>
    <div class="">
    {{-- <div class="d-flex"> --}}
        <form name="searchForm" action="" method="get">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label for="firstName"> </label>
                    <select class="form-control select2" name="combination">
                        <option value="">All</option>
                        @foreach($combinationList as $key => $combination)
                            <option <?php echo Request::get('combination') == $key ? 'selected' : '' ?> value="{{ $key }}">{{ $combination }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label for="lastName">  </label>
                    <input type="text" class="form-control form-control-sm new" placeholder="name" aria-controls="datable_1" name="name" value="{{ Request::get('name') }}">
                </div>
                <div class="col-md-4 form-group">
                <button class="btn btn-tool btn-danger" name="search" value="search" style="margin-top: 19px;">Search </button>
                <a class="btn btn-tool btn-info" href ="{{ route('user.chat.bot.message.create') }}" style="margin-top: 19px;">Create Response</a>
                </div>
            </div>
        </form>
        {{-- </div> --}}
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
                                <th>#</th>
                                <th>Name</th>
                                <th>App Name</th>
                                <th>App Value</th>
                                <th>Message</th>
                                <th>Response</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($messageList as $key=>$data)
                                <tr>
                                    <td class="serial">{{ $key + $messageList->firstItem()}} </td>
                                    <td> <span class="name">{{ $data->name }}</span> </td>
                                    <td><span style="font-weight: bold;">{{ strtoupper($data->app_name) }}</span></td>
                                    <?php
                                        $appName = "";
                                        foreach($allData as $menu) {
                                            if(($menu->type === $data->app_name) && $menu->id == $data->app_value) {
                                                  $appName =  strtoupper($menu->name);
                                            }
                                        }

                                    ?>
                                    <td >{{ $appName }}</td>
                                    <td>

                                        <?php
                                        $string = strip_tags(rawurldecode($data->message));
                                        if (strlen($string) > 10) {

                                            // truncate string
                                            $stringCut = substr($string, 0, 10);
                                            $endPoint = strrpos($stringCut, ' ');
                                            //if the string doesn't contain any space then it will cut without word basis.
                                            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
                                            $string .= '... <i class="fa fa-eye description" data-toggle="modal" data-target="#myModal"></i>';
                                        }
                                        ?>
                                        <?php  echo $string ?>
                                        <input type="hidden" value="{{ rawurldecode($data->message) }}">
                                    </td>
                                    <td >{{ $data->type }}</td>
                                    <td >{{ $data->created_at }}</td>
                                    <td ><a href="{{ route('user.chat.bot.message.edit',['id' => Crypt::encrypt($data->id) , 'combination' => $data->typeValue]) }}" class=""><i class="fa fa-edit"></i></a>&nbsp;&nbsp; <a href="#" onclick="__appActionsResponse('{{ Crypt::encrypt($data->id) }}','{{ strtolower($data->type) }}')"><i class="fa fa-trash" data-toggle="tooltip" data-original-title="Delete Chat Instance" ></i></a>&nbsp;&nbsp;</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"> No Data in the list</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <br>
                    @if($messageList->total()!=0)
                        <div class="hk-row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info" id="datable_1_info" role="status" aria-live="polite">
                                    Showing {{$messageList->firstItem()}} to {{$messageList->lastItem()}} of {{$messageList->total()}} entries
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers pull-right" id="datable_1_paginate">
                                    <ul class="pagination">
                                        {{ $messageList->appends(Request::get('name'))->links() }}
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
<div class="container">
    <div class="modal" id="myModal">
      <div class="modal-dialog">
        <div class="modal-content">

          <!-- Modal Header -->
          <div class="modal-header">
            <h4 class="modal-title">Message</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>

          <!-- Modal body -->

            <textarea class="modal-body" disabled rows="15" cols="60">
            </textarea>


          <!-- Modal footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>

  </div>
<style type="text/css">
    .select2-container .select2-selection--single {
        height: 40px ! important;
    }
  .select2-container--default .select2-selection--single .select2-selection__rendered, .select2-results__option {
    text-transform: uppercase;
  }
</style>
<script src="{{ asset('dist/js/jquery.min.js') }}"></script>
<script>
$(".description").click(function(e) {
    let value = $(this).parent().find("input").val();
    $(".modal-body").html(value);
});
    function __appActionsResponse(id,type){

        if (confirm('Are you sure you want to permanently delete this response message?')){

            $.ajax(
                {
                    url: '{{ route('user.chat.bot.message.response.delete') }}',
                    dataType: 'json', // what to expect back from the PHP script
                    cache: false,
                    data: { _token: "{{ csrf_token() }}", id : id ,type : type } ,
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
<script src="{{ asset('dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('dist/js/select2-data.js') }}"></script>
<script src="{{ asset('dist/js/custom-script.js') }}"></script>
@endsection
