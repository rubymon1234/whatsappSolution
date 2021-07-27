@if(session('error_message'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('error_message') }}
    </div>
@endif
@if(session('success_message'))
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('success_message') }}
    </div>
@endif
@if(session('warning_message'))
    <div class="alert alert-warning alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session('warning_message') }}
    </div>
@endif