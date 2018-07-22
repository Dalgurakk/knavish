@extends('layouts.master2')
@section('title','Manage Hotel')
@section('page-css')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.select2-container--bootstrap .select2-selection--single {
    height: 40px;
    line-height: 2.0;
}
</style>
@stop

@section('page-title','Manage Hotel')
@section('page-sub-title','define prices, allotments and more...')
@section('page-toolbar')
<div class="page-toolbar">
    <!--div class="btn-group pull-right">
        <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
            <i class="fa fa-angle-down"></i>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
            <li>
                <a href="#">
                    <i class="icon-bell"></i> Action</a>
            </li>
            <li>
                <a href="#">
                    <i class="icon-shield"></i> Another action</a>
            </li>
            <li>
                <a href="#">
                    <i class="icon-user"></i> Something else here</a>
            </li>
            <li class="divider"> </li>
            <li>
                <a href="#">
                    <i class="icon-bag"></i> Separated link</a>
            </li>
        </ul>
    </div-->
    <div class="inputs" style="padding: 0;">
        <div class="portlet-input input-inline input-large select2-bootstrap-append">
            <div class="input-group input-group-md select2-bootstrap-append ">
                <select class="form-control select-hotel ">
                @foreach($hotels as $h)
                    <option value="{{ $h->id }}">{{ $h->name }}</option>
                @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<!--div class="inputs" style="padding: 0;">
                    <div class="portlet-input input-inline input-large select2-bootstrap-append">
                        <div class="input-group input-group-md select2-bootstrap-append">
                            <select class="form-control select-hotel">
                            @foreach($hotels as $h)
                                <option value="{{ $h->id }}">{{ $h->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div-->
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <!--div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-building-o"></i>Hotel Selection </div>
                <div class="inputs" style="padding: 0;">
                    <div class="portlet-input input-inline input-large select2-bootstrap-append">
                        <div class="input-group input-group-md select2-bootstrap-append">
                            <select class="form-control select-hotel">
                            @foreach($hotels as $h)
                                <option value="{{ $h->id }}">{{ $h->name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="portlet-body">


            </div>
        </div-->
        <div class="row">
                            <div class="col-md-4 ">
                                <!-- BEGIN Portlet PORTLET-->
                                <div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-calendar"></i>Range </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="scroller" style="height:200px">
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Portlet PORTLET-->
                            </div>
                            <div class="col-md-4 ">
                                <!-- BEGIN Portlet PORTLET-->
                                <div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-navicon"></i>Rows </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="scroller" style="height:200px">
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Portlet PORTLET-->
                            </div>
                            <div class="col-md-4 ">
                                <!-- BEGIN Portlet PORTLET-->
                                <div class="portlet box green">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-hotel"></i>Room Type </div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="scroller" style="height:200px">
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                            <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula,
                                                eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis
                                                consectetur purus sit amet fermentum. est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- END Portlet PORTLET-->
                            </div>
                        </div>
    </div>
</div>

@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
        $(".select-hotel").select2({
            width: "off"
        });
    });
</script>
@stop