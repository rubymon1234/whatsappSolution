@extends('layouts.master')
@section('content')
    <div class="pageCenter">
        <div class="topSection">
            <h1 class="left">Login</h1>
            <div class="search right">
                <form action="#">
                    <div class="inputHolderType1 searchV1 right">
                        <input type="text" placeholder="Search By Region">
                        <input type="submit" class="searchBtn" placeholder="Search">
                    </div>
                    <div class="filterType1 right">
                            <a href="javascript:void(0)" class="filter">Filter</a>
                            <div class="filterContent">
                                <span>Choose by city</span>
                                <ul>
                                    <li><label><input type="checkbox">Filter 1</label></li>
                                    <li><label><input type="checkbox">Filter 2</label></li>
                                    <li><label><input type="checkbox">Filter 3</label></li>
                                    <li><label><input type="checkbox">Filter 4</label></li>
                                </ul>
                            </div>
                        </div>
                </form>
            </div>
            <div class="customClear"></div>
        </div>
    </div>
@endsection