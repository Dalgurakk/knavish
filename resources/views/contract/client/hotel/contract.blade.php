@extends('layouts.master2')
@section('title','Contracts')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-multi-select/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.css') }}" rel="stylesheet" type="text/css" />
<style>
    .ms-container .ms-list { height: 150px; }
    .ms-container { width: unset; }
    .tabbable-custom { margin-bottom: 0; }
    .tabbable-line > .nav-tabs > li { border-bottom: 0 !important; }
    .custom-radio { margin-bottom: 11px !important; }
    .tabbable-custom { margin-bottom: 0px; }
    .ms-container .ms-selectable li.ms-elem-selectable { padding: 5px 10px !important; }
    .ms-container .ms-selection li.ms-elem-selection { padding: 5px 10px !important; }
</style>
@stop

@section('page-title','Manage Contracts')
@section('page-sub-title','show, insert, update and delete contracts')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-file-text-o"></i>Contracts List </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default search" href="javascript:;">
                        <i class="fa fa-search"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default add" data-toggle="modal" href="#modal-add">
                        <i class="fa fa-plus"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default reload" href="javascript:;">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <div class="btn-group">
                        <a class="btn btn-circle btn-icon-only btn-default dropdown-toggle lenght btn-dropdown" data-toggle="dropdown" href="javascript:;">10</a>
                        <ul class="dropdown-menu dropdown-options">
                            <li>
                                <a href="javascript:;" class="lenght-option" data="10">10</a>
                            </li>
                            <li>
                                <a href="javascript:;" class="lenght-option" data="25">25</a>
                            </li>
                            <li>
                                <a href="javascript:;" class="lenght-option" data="50">50</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div id="search-section" style="display: none;">
                    <form>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-file-text-o"></i>
                                        <input type="text" class="form-control" name="name" placeholder="Denomination"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-user"></i>
                                        <input type="text" class="form-control" name="client" placeholder="Client"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" class="form-control" name="valid-from" placeholder="From"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" class="form-control" name="valid-to" placeholder="To"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon left">
                                        <i class="fa fa-check"></i>
                                        <select class="form-control" name="active">
                                            <option value="">Enabled/Disabled</option>
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>
                                        </select> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <button type="submit" class="btn green btn-search-submit"><i class="fa fa-search"></i> Search</button>
                                    <button class="btn default btn-search-reset"><!--i class="fa fa-eraser"></i--> Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table id="table" class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable" width="100%" cellspacing="0">
                    <thead>
                        <tr role="row" class="heading">
                            <th class="">Id</th>
                            <th class="">Denomination</th>
                            <th class="">Client</th>
                            <th class="">Valid From</th>
                            <th class="">Valid To</th>
                            <th class="">Status</th>
                            <th class="">Enable</th>
                            <th class="" style="min-width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-add" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Add Contract</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text-o"></i> General Data </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Denomination</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-file-text-o"></i>
                                        <input type="text" class="form-control" placeholder="Denomination" name="name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label class="control-label">Contract</label>
                                <div class="form-group">
                                    <select class="form-control ajax-select select-contract" name="contract"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <label class="control-label">Client</label>
                                <div class="form-group">
                                    <select class="form-control ajax-select select-client" name="client"></select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label class="control-label">Price Rate</label>
                                <div class="form-group">
                                    <select class="form-control" name="price-rate"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Valid From</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" type="text" value="" name="valid-from" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Valid To</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" type="text" value="" name="valid-to" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <div class="mt-checkbox-list">
                                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                                            <input type="checkbox" value="1" name="active"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet box green show-hotel">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-building-o"></i> Hotel </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Hotel</label>
                                    <input type="text" class="form-control trigger-location" name="hotel" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control trigger-location" name="country-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control trigger-location" name="state-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control trigger-location" name="city-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control" name="postal-code" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Turistic License</label>
                                    <input type="text" class="form-control" name="turistic-licence" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label style="margin-bottom: 11px;">Category</label>
                                    <select class="hotel-category" name="category">
                                      <option value="">Select Category</option>
                                      <option value="1">1</option>
                                      <option value="2">2</option>
                                      <option value="3">3</option>
                                      <option value="4">4</option>
                                      <option value="5">5</option>
                                      <option value="6">6</option>
                                      <option value="7">7</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Hotel Chain</label>
                                    <input type="text" class="form-control" name="hotel-chain" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Admin. Phone</label>
                                    <input type="text" class="form-control" name="admin-phone" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Admin. Fax</label>
                                    <input type="text" class="form-control" name="admin-fax" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Web Site</label>
                                    <input type="text" class="form-control" name="web-site" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-male"></i> Pax Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-pax-type" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th> Id </th>
                                        <th> Pax Type </th>
                                        <th> From </th>
                                        <th> To </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-hotel"></i> Room Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th> Id </th>
                                        <th> Room Type </th>
                                        <th> Max Pax </th>
                                        <th> Max AD </th>
                                        <th> Min AD </th>
                                        <th> Max CH </th>
                                        <th> Min CH </th>
                                        <th> Max INF </th>
                                        <th> Min INF </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cutlery"></i> Board Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-board-type" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th> Id </th>
                                        <th> Board Type </th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green" data="apply"><i class="fa fa-repeat"></i> Apply</button>
        <button type="submit" class="btn green" data="accept"><i class="fa fa-check"></i> Accept</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
    </form>
</div>

<div id="modal-info" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Contract Data</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text-o"></i> General Data </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label>Denomination</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-file-text-o"></i>
                                        <input type="text" class="form-control" name="name" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <label class="control-label">Contract</label>
                                <input type="text" class="form-control" name="contract" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Client</label>
                                    <input type="text" class="form-control" name="client" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Price Rate</label>
                                    <input type="text" class="form-control" name="price-rate" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Valid From</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" type="text" value="" name="valid-from" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Valid To</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" type="text" value="" name="valid-to" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <div class="mt-checkbox-list">
                                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                                            <input type="checkbox" value="1" name="active" onclick="return false;"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet box green show-hotel">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-building-o"></i> Hotel </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Hotel</label>
                                    <input type="text" class="form-control trigger-location" name="hotel" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control trigger-location" name="country-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control trigger-location" name="state-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control trigger-location" name="city-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control" name="postal-code" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Turistic License</label>
                                    <input type="text" class="form-control" name="turistic-licence" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label style="margin-bottom: 11px;">Category</label>
                                    <select class="hotel-category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Hotel Chain</label>
                                    <input type="text" class="form-control" name="hotel-chain" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Admin. Phone</label>
                                    <input type="text" class="form-control" name="admin-phone" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Admin. Fax</label>
                                    <input type="text" class="form-control" name="admin-fax" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Web Site</label>
                                    <input type="text" class="form-control" name="web-site" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-male"></i> Pax Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-pax-type" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Pax Type </th>
                                    <th> From </th>
                                    <th> To </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-hotel"></i> Room Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Room Type </th>
                                    <th> Max Pax </th>
                                    <th> Max AD </th>
                                    <th> Min AD </th>
                                    <th> Max CH </th>
                                    <th> Min CH </th>
                                    <th> Max INF </th>
                                    <th> Min INF </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cutlery"></i> Board Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-board-type" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Board Type </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>

<div id="modal-edit" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Edit Contract</h4>
    </div>
    <form id="form-edit">
        <div class="modal-body">
            <input type="hidden" name="id">
            <input type="hidden" name="hotel-contract-id">
            <input type="hidden" name="client-id">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box green ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-file-text-o"></i> General Data </div>
                        </div>
                        <div class="portlet-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label>Denomination</label>
                                        <div class="input-icon left">
                                            <i class="fa fa-file-text-o"></i>
                                            <input type="text" class="form-control" placeholder="Denomination" name="name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label class="control-label">Contract</label>
                                    <div class="form-group">
                                        <select class="form-control ajax-select select-contract" name="contract"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <label class="control-label">Client</label>
                                    <div class="form-group">
                                        <select class="form-control ajax-select select-client" name="client"></select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <label class="control-label">Price Rate</label>
                                    <div class="form-group">
                                        <select class="form-control" name="price-rate"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Valid From</label>
                                        <div class="input-icon left">
                                            <i class="fa fa-calendar"></i>
                                            <input class="form-control date-picker" type="text" value="" name="valid-from" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Valid To</label>
                                        <div class="input-icon left">
                                            <i class="fa fa-calendar"></i>
                                            <input class="form-control date-picker" type="text" value="" name="valid-to" readonly/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <div class="mt-checkbox-list">
                                            <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                                                <input type="checkbox" value="1" name="active"/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet box green show-hotel">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-building-o"></i> Hotel </div>
                        </div>
                        <div class="portlet-body">
                            <input type="hidden" name="hotel-id">
                            <div class="row">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label">Hotel</label>
                                        <input type="text" class="form-control trigger-location" name="hotel" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type="text" class="form-control trigger-location" name="country-text" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" class="form-control trigger-location" name="state-text" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control trigger-location" name="city-text" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" class="form-control" name="address" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Postal Code</label>
                                        <input type="text" class="form-control" name="postal-code" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Turistic License</label>
                                        <input type="text" class="form-control" name="turistic-licence" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label style="margin-bottom: 11px;">Category</label>
                                        <select class="hotel-category" name="category">
                                            <option value="">Select Category</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Hotel Chain</label>
                                        <input type="text" class="form-control" name="hotel-chain" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" name="email" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Admin. Phone</label>
                                        <input type="text" class="form-control" name="admin-phone" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Admin. Fax</label>
                                        <input type="text" class="form-control" name="admin-fax" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Web Site</label>
                                        <input type="text" class="form-control" name="web-site" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="portlet box green ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-male"></i> Pax Types </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <input type="hidden" class="hotel-type" name="count-pax-type" value="0">
                                <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-pax-type" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th> Id </th>
                                        <th> Pax Type </th>
                                        <th> From </th>
                                        <th> To </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="portlet box green ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-hotel"></i> Room Types </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <input type="hidden" name="count-room-type" class="hotel-type" value="0">
                                <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th> Id </th>
                                        <th> Room Type </th>
                                        <th> Max Pax </th>
                                        <th> Max AD </th>
                                        <th> Min AD </th>
                                        <th> Max CH </th>
                                        <th> Min CH </th>
                                        <th> Max INF </th>
                                        <th> Min INF </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="portlet box green ">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-cutlery"></i> Board Types </div>
                        </div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <input type="hidden" class="hotel-type" name="count-board-type" value="0">
                                <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-board-type" width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th> Id </th>
                                        <th> Board Type </th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <!--button type="submit" class="btn green" data="apply"><i class="fa fa-repeat"></i> Apply</button-->
            <button type="submit" class="btn green" data="accept"><i class="fa fa-check"></i> Accept</button>
            <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
        </div>
    </form>
</div>
@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-bar-rating-master/dist/jquery.barrating.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/fuelux/js/spinner.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}" type="text/javascript"></script>
<script>
    var routeRead = "{{ route('contract.client.hotel.read') }}";
    var routeSetting = "{{ route('contract.client.hotel.settings') }}";
    var routeClient = "{{ route('administration.user.search.client.active') }}";
    var routeProvider = "{{ route('contract.provider.hotel.search.active') }}";
    var routeCreate = "{{ route('contract.client.hotel.create') }}";
    var routeUpdate = "{{ route('contract.client.hotel.update') }}";
    var routeDelete = "{{ route('contract.client.hotel.delete') }}";
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/contract/client/hotel/contract.min.js') }}" type="text/javascript"></script>
@stop