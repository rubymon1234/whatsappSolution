@extends('layouts.master')
@section('content')
<div class="container-fluid mt-xl-20 mt-sm-30 mt-15">
    <div class="alert alert-danger alert-wth-icon alert-dismissible fade show" role="alert">
	    <span class="alert-icon-wrap"><i class="zmdi zmdi-notifications-active"></i></span> Your have no Permission to access this page .
	    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	        <span aria-hidden="true">×</span>
	    </button>
	</div>
</div>      
@endsection
