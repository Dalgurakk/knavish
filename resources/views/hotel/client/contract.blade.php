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
                                        <th> Min Pax </th>
                                        <th> Min AD </th>
                                        <th> Min CH </th>
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
                                    <th> Min Pax </th>
                                    <th> Min AD </th>
                                    <th> Min CH </th>
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
                                        <th> Min Pax </th>
                                        <th> Min AD </th>
                                        <th> Min CH </th>
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
            <button type="submit" class="btn green" data="apply"><i class="fa fa-repeat"></i> Apply</button>
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
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
        var needUpdate = false;

        $.fn.dataTable.ext.errMode = 'none';
        var table = $('#table').on('error.dt', function(e, settings, techNote, message) {

        }).on( 'processing.dt', function ( e, settings, processing ) {
            App.showMask(processing, $(this));
            App.reloadToolTips();
        }).on('init.dt', function() {

        }).DataTable({
            "processing": true,
            "serverSide": true,
            "sDom": "ltip",
            "iDisplayLength" : 10,
            "ajax": {
                "url": "{{ route('hotel.contract.client.read') }}",
                "type": "POST",
                "complete": function(xhr, textStatus) {
                    if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    $('.br-readonly').on('click', function(e) {
                        e.preventDefault();
                    });
                }
            },
            "order": [[ 1, "asc" ]],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'name', name: 'name' },
                { data: 'client', name: 'client' },
                { data: 'valid_from', name: 'valid_from' },
                { data: 'valid_to', name: 'valid_to' },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    "data": function (row, type, val, meta ) {
                        var data = '';
                        if (row.status == '3')
                            data = '<span class="label label-sm label-danger"> Finished </span>';
                        else if (row.status == '2')
                            data = '<span class="label label-sm label-success"> In Progress </span>';
                        else if (row.status == '1')
                            data = '<span class="label label-sm label-warning"> Pending </span>';
                        return data;
                    }
                },
                {
                    data: 'active',
                    name: 'active',
                    "className": "dt-center",
                    "data": function ( row, type, val, meta ) {
                        var data = '<span><i class="fa fa-close dt-active dt-active-0"></i></span>';
                        if (row.active == 1)
                            data = '<span><i class="fa fa-check dt-active dt-active-1"></i></span>';
                        return data;
                    }
                },
                {
                    targets: 'actions',
                    orderable: false,
                    name: 'actions',
                    "className": "dt-center",
                    "data": function ( row, type, val, meta ) {
                        //var contract = row.contract;
                        var data =
                        '<form method="get" action="{{ route("hotel.contract.client.settings") }}">' +
                            '<input type="hidden" name="id" value="' + row.id + '">' +
                            '<div class="dt-actions">' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info">' +
                                '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>'+
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-edit" data-toggle="modal" href="#modal-edit">' +
                                '<i class="icon-pencil btn-action-icon"></i></a>' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-delete" href="javascript:;" data-popout="true" data-placement="left"' +
                                'data-btn-ok-label="Yes" data-btn-ok-class="btn-sm btn-success"  data-btn-ok-icon-content="check" ' +
                                'data-btn-cancel-label="No" data-btn-cancel-class="btn-sm btn-danger" data-btn-cancel-icon-content="close" data-title="Are you sure?" data-content="">' +
                                '<i class="icon-trash btn-action-icon"></i></a>' +
                            '<button type="submit" class="btn btn-default btn-circle btn-icon-only btn-action dt-setting">' +
                                '<i class="icon-settings btn-action-icon"></i></button>' +
                            '</div>' +
                        '</form>';
                        return data;
                    }
                }
            ]
        });

        $('#table_length').hide();

        $('.search').on('click', function (e) {
            $('#search-section').slideToggle();
        });

        $('.reload').on('click', function (e) {
            table.draw();
        });

        $('.add').on('click', function () {
            formAdd.validate().resetForm();
            formAdd[0].reset();
            $('#modal-add :input[name=category]').barrating('set', '');
            tableAddPaxType.api().clear().draw();
            tableAddRoomType.api().clear().draw();
            tableAddBoardType.api().clear().draw();
            $('#modal-add .select-client').val(null).trigger('change');
            $('#modal-add .select-contract').val(null).trigger('change');
            $('#modal-add .ms-elem-selectable').removeClass('ms-hover');
            $('#modal-add :input[name="price-rate"]').empty();
        });

        $('.lenght-option').on('click', function () {
            var value = $(this).attr('data');
            $(this).parent().parent().prev('a').text(value);
            var select = $('select[name=table_length]');
            select.val(value);
            select.change();
        });

        var tableAddPaxType = $('#modal-add .table-pax-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 2, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableAddBoardType = $('#modal-add .table-board-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 1, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableAddRoomType = $('#modal-add .table-room-type').dataTable({
            "sDom": "t",
            "lengthMenu": [[-1], ["All"]],
            "order": [[ 1, "asc" ]],
            "autoWidth": false,
            "columnDefs": [
                { 'visible': false, 'targets': [0] },
                { 'targets': [1], width: '35%' },
                { 'orderable': false, 'targets': [2] },
                { 'orderable': false, 'targets': [3] },
                { 'orderable': false, 'targets': [4] },
                { 'orderable': false, 'targets': [5] },
                { 'orderable': false, 'targets': [6] }
            ],
            "language": {
                "emptyTable": "No room type selected"
            }
        });

        var tableEditPaxType = $('#modal-edit .table-pax-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 2, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableEditBoardType = $('#modal-edit .table-board-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 1, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableEditRoomType = $('#modal-edit .table-room-type').dataTable({
            "sDom": "t",
            "lengthMenu": [[-1], ["All"]],
            "order": [[ 1, "asc" ]],
            "autoWidth": false,
            "columnDefs": [
                { 'visible': false, 'targets': [0] },
                { 'targets': [1], width: '35%' },
                { 'orderable': false, 'targets': [2] },
                { 'orderable': false, 'targets': [3] },
                { 'orderable': false, 'targets': [4] },
                { 'orderable': false, 'targets': [5] },
                { 'orderable': false, 'targets': [6] }
            ],
            "language": {
                "emptyTable": "No room type selected"
            }
        });

        var tableInfoPaxType = $('#modal-info .table-pax-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 2, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableInfoBoardType = $('#modal-info .table-board-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 1, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableInfoRoomType = $('#modal-info .table-room-type').dataTable({
            "sDom": "t",
            "lengthMenu": [[-1], ["All"]],
            "order": [[ 1, "asc" ]],
            "autoWidth": false,
            "columnDefs": [
                { 'visible': false, 'targets': [0] },
                { 'targets': [1], width: '35%' },
                { 'orderable': false, 'targets': [2] },
                { 'orderable': false, 'targets': [3] },
                { 'orderable': false, 'targets': [4] },
                { 'orderable': false, 'targets': [5] },
                { 'orderable': false, 'targets': [6] }
            ],
            "language": {
                "emptyTable": "No room type selected"
            }
        });

        $('.hotel-category').barrating({
            theme: 'fontawesome-stars',
            readonly: true
        });

        function format(repo) {
            if (repo.loading) return repo.text;
            var markup =
                "<div class=''>" +
                    "<div class=''>" + repo.name + "</div>"+
                "</div>";
            return markup;
        }

        function formatSelection(repo) {
            return repo.name;
        }

        $("#modal-add :input[name=client]").select2({
            width: "off",
            ajax: {
                url: "{{ route('user.search.client.active') }}",
                "type": "POST",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 3,
            templateResult: format,
            templateSelection: formatSelection
        });

        $("#modal-add :input[name=contract]").select2({
            width: "off",
            ajax: {
                url: "{{ route('hotel.contract.provider.search.active') }}",
                "type": "POST",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 3,
            templateResult: format,
            templateSelection: formatSelection
        });

        $('#modal-add :input[name=contract]').on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            var hotel = values.hotel;
            var paxTypes = values.pax_types;
            var boardTypes = values.board_types;
            var roomTypes = values.room_types;
            var priceRates = values.price_rates;
            if(values.selected) {
                $('#modal-add :input[name=valid-from]').val(moment(values.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY'));
                $('#modal-add :input[name=valid-to]').val(moment(values.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));

                $('#modal-add .show-hotel :input[name=hotel]').val(hotel.name);
                $('#modal-add .show-hotel :input[name=country-text]').val(hotel.country.name);
                $('#modal-add .show-hotel :input[name=state-text]').val(hotel.state.name);
                $('#modal-add .show-hotel :input[name=city-text]').val(hotel.city.name);
                $('#modal-add .show-hotel :input[name=postal-code]').val(hotel.postal_code);
                $('#modal-add .show-hotel :input[name=address]').val(hotel.address);
                $('#modal-add .show-hotel :input[name=category]').barrating('readonly', true);
                var category = hotel.category != null ? hotel.category : '';
                $('#modal-add .show-hotel :input[name=category]').barrating('set', category);
                $('#modal-add .show-hotel :input[name=hotel-chain]').val(hotel.hotel_chain.name);
                $('#modal-add .show-hotel :input[name=admin-phone]').val(hotel.admin_phone);
                $('#modal-add .show-hotel :input[name=admin-fax]').val(hotel.admin_fax);
                $('#modal-add .show-hotel :input[name=web-site]').val(hotel.web_site);
                $('#modal-add .show-hotel :input[name=turistic-licence]').val(hotel.turistic_licence);
                $('#modal-add .show-hotel :input[name=email]').val(hotel.email);

                $('#modal-add :input[name="price-rate"]').empty();
                for (var i = 0; i < priceRates.length; i++) {
                    var method = '';
                    if(priceRates[i].type == '1')
                        method = '(+ ' + priceRates[i].value + '%)';
                    else if(priceRates[i].type == '2')
                        method = '(+ $' + priceRates[i].value + ')';
                    var option = '<option value="' + priceRates[i].id  + '">' + priceRates[i].market.name + ' ' + method + '</option>';
                    $('#modal-add :input[name="price-rate"]').append(option);
                }

                tableAddPaxType.api().clear();
                for (var i = 0; i < paxTypes.length; i++) {
                    tableAddPaxType.api().row.add([
                        paxTypes[i].id,
                        paxTypes[i].code + ': ' + paxTypes[i].name,
                        paxTypes[i].agefrom,
                        paxTypes[i].ageto,
                    ]).draw( false );
                }
                tableAddPaxType.api().columns.adjust().draw();

                tableAddBoardType.api().clear();
                for (var i = 0; i < boardTypes.length; i++) {
                    tableAddBoardType.api().row.add([
                        boardTypes[i].id,
                        boardTypes[i].code + ': ' + boardTypes[i].name
                    ]).draw( false );
                }
                tableAddBoardType.api().columns.adjust().draw();

                tableAddRoomType.api().clear();
                for (var i = 0; i < roomTypes.length; i++) {
                    tableAddRoomType.api().row.add([
                        roomTypes[i].id,
                        roomTypes[i].code + ': ' + roomTypes[i].name,
                        roomTypes[i].maxpax,
                        roomTypes[i].minpax,
                        roomTypes[i].minadult,
                        roomTypes[i].minchildren,
                        roomTypes[i].minchildren
                    ]).draw( false );
                }
                tableAddRoomType.api().columns.adjust().draw();
            }
        });

        $("#modal-edit :input[name=client]").select2({
            width: "off",
            ajax: {
                url: "{{ route('user.search.client.active') }}",
                "type": "POST",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 3,
            templateResult: format,
            templateSelection: formatSelection
        });

        $('#modal-edit :input[name=client]').on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            if(values.selected) {
                $('#modal-edit :input[name=client-id]').val(values.id);
            }
        });

        $("#modal-edit :input[name=contract]").select2({
            width: "off",
            ajax: {
                url: "{{ route('hotel.contract.provider.search.active') }}",
                "type": "POST",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 3,
            templateResult: format,
            templateSelection: formatSelection
        });

        $('#modal-edit :input[name=contract]').on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            var hotel = values.hotel;
            var paxTypes = values.pax_types;
            var boardTypes = values.board_types;
            var roomTypes = values.room_types;
            var priceRates = values.price_rates;
            if(values.selected) {
                $('#modal-edit :input[name=hotel-contract-id]').val(values.id);
                $('#modal-edit :input[name="valid-from"]').val(values.valid_from);
                $('#modal-edit :input[name="valid-to"]').val(values.valid_to);

                $('#modal-edit .show-hotel :input[name=hotel]').val(hotel.name);
                $('#modal-edit .show-hotel :input[name=country-text]').val(hotel.country.name);
                $('#modal-edit .show-hotel :input[name=state-text]').val(hotel.state.name);
                $('#modal-edit .show-hotel :input[name=city-text]').val(hotel.city.name);
                $('#modal-edit .show-hotel :input[name=postal-code]').val(hotel.postal_code);
                $('#modal-edit .show-hotel :input[name=address]').val(hotel.address);
                $('#modal-edit .show-hotel :input[name=category]').barrating('readonly', true);
                var category = hotel.category != null ? hotel.category : '';
                $('#modal-edit .show-hotel :input[name=category]').barrating('set', category);
                $('#modal-edit .show-hotel :input[name=hotel-chain]').val(hotel.hotel_chain.name);
                $('#modal-edit .show-hotel :input[name=admin-phone]').val(hotel.admin_phone);
                $('#modal-edit .show-hotel :input[name=admin-fax]').val(hotel.admin_fax);
                $('#modal-edit .show-hotel :input[name=web-site]').val(hotel.web_site);
                $('#modal-edit .show-hotel :input[name=turistic-licence]').val(hotel.turistic_licence);
                $('#modal-edit .show-hotel :input[name=email]').val(hotel.email);

                $('#modal-edit :input[name="price-rate"]').empty();
                for (var i = 0; i < priceRates.length; i++) {
                    var method = '';
                    if(priceRates[i].type == '1')
                        method = '(+ ' + priceRates[i].value + '%)';
                    else if(priceRates[i].type == '2')
                        method = '(+ $' + priceRates[i].value + ')';
                    var option = '<option value="' + priceRates[i].id  + '">' + priceRates[i].market.name + ' ' + method + '</option>';
                    $('#modal-edit :input[name="price-rate"]').append(option);
                }

                tableEditPaxType.api().clear();
                for (var i = 0; i < paxTypes.length; i++) {
                    tableEditPaxType.api().row.add([
                        paxTypes[i].id,
                        paxTypes[i].code + ': ' + paxTypes[i].name,
                        paxTypes[i].agefrom,
                        paxTypes[i].ageto,
                    ]).draw( false );
                }
                tableEditPaxType.api().columns.adjust().draw();

                tableEditBoardType.api().clear();
                for (var i = 0; i < boardTypes.length; i++) {
                    tableEditBoardType.api().row.add([
                        boardTypes[i].id,
                        boardTypes[i].code + ': ' + boardTypes[i].name
                    ]).draw( false );
                }
                tableEditBoardType.api().columns.adjust().draw();

                tableEditRoomType.api().clear();
                for (var i = 0; i < roomTypes.length; i++) {
                    tableEditRoomType.api().row.add([
                        roomTypes[i].id,
                        roomTypes[i].code + ': ' + roomTypes[i].name,
                        roomTypes[i].maxpax,
                        roomTypes[i].minpax,
                        roomTypes[i].minadult,
                        roomTypes[i].minchildren,
                        roomTypes[i].minchildren
                    ]).draw( false );
                }
                tableEditRoomType.api().columns.adjust().draw();
            }
        });

        var formAdd = $('#form-add');
        formAdd.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                name: {
                    required: true
                },
                contract: {
                    required: true
                },
                client: {
                    required: true
                },
                "price-rate": {
                    required: true
                }
            },
            errorPlacement: function (error, element) {
                if (element.parents('.mt-radio-list').size() > 0 || element.parents('.mt-checkbox-list').size() > 0) {
                    if (element.parents('.mt-radio-list').size() > 0) {
                        error.appendTo(element.parents('.mt-radio-list')[0]);
                    }
                    if (element.parents('.mt-checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.mt-checkbox-list')[0]);
                    }
                } else if (element.parents('.mt-radio-inline').size() > 0 || element.parents('.mt-checkbox-inline').size() > 0) {
                    if (element.parents('.mt-radio-inline').size() > 0) {
                        error.appendTo(element.parents('.mt-radio-inline')[0]);
                    }
                    if (element.parents('.mt-checkbox-inline').size() > 0) {
                        error.appendTo(element.parents('.mt-checkbox-inline')[0]);
                    }
                } else if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                }else if (element.hasClass("ajax-select")) {
                    error.insertAfter(element.next());
                }else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) {
                toastr['error']("Please check the entry fields.", "Error");
            },
            highlight: function (element) {
                $(element)
                    .closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element)
                    .closest('.form-group').removeClass('has-error');
            },
            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                var option = $(form).find("button[type=submit]:focus").attr('data');
                var priceRate = $('#modal-add :input[name="price-rate"]').val();
                var name = $('#modal-add :input[name="name"]').val();
                var contract = $('#modal-add :input[name="contract"]').val();
                var client = $('#modal-add :input[name="client"]').val();
                var active = $('#modal-add :input[name="active"]').prop('checked') ? '1' : '0';

                $.ajax({
                    "url": "{{ route('hotel.contract.client.create') }}",
                    "type": "POST",
                    "data": {
                        "price-rate": priceRate,
                        name: name,
                        contract: contract,
                        client: client,
                        active: active
                    },
                    "beforeSend": function() {
                        App.showMask(true, formAdd);
                    },
                    "complete": function(xhr, textStatus) {
                        App.showMask(false, formAdd);
                        if (xhr.status != '200') {
                            toastr['error']("Please check your connection and try again.", "Error on loading the content");
                        }
                        else {
                            var response = $.parseJSON(xhr.responseText);
                            if (response.status == 'success') {
                                toastr['success'](response.message, "Success");
                                needUpdate = true;
                                if (option == 'accept') {
                                    $(form).find("button.cancel-form").click();
                                }
                                formAdd.validate().resetForm();
                                formAdd[0].reset();
                                $('#modal-add :input[name=category]').barrating('set', '');
                                tableAddPaxType.api().clear().draw();
                                tableAddRoomType.api().clear().draw();
                                tableAddBoardType.api().clear().draw();
                                $('#modal-add .select-client').val(null).trigger('change');
                                $('#modal-add .select-contract').val(null).trigger('change');
                                $('#modal-add .ms-elem-selectable').removeClass('ms-hover');
                                $('#modal-add :input[name="price-rate"]').empty();
                            }
                            else {
                                toastr['error'](response.message, "Error");
                            }
                        }
                    }
                });
            }
        });

        var formEdit = $('#form-edit');
        formEdit.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                name: {
                    required: true
                },
                "price-rate": {
                    required: true
                }
            },
            errorPlacement: function (error, element) {
                if (element.parents('.mt-radio-list').size() > 0 || element.parents('.mt-checkbox-list').size() > 0) {
                    if (element.parents('.mt-radio-list').size() > 0) {
                        error.appendTo(element.parents('.mt-radio-list')[0]);
                    }
                    if (element.parents('.mt-checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.mt-checkbox-list')[0]);
                    }
                } else if (element.parents('.mt-radio-inline').size() > 0 || element.parents('.mt-checkbox-inline').size() > 0) {
                    if (element.parents('.mt-radio-inline').size() > 0) {
                        error.appendTo(element.parents('.mt-radio-inline')[0]);
                    }
                    if (element.parents('.mt-checkbox-inline').size() > 0) {
                        error.appendTo(element.parents('.mt-checkbox-inline')[0]);
                    }
                } else if (element.parent(".input-group").size() > 0) {
                    error.insertAfter(element.parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.hasClass("ajax-select")) {
                    error.insertAfter(element.next());
                } else {
                    error.insertAfter(element);
                }
            },
            invalidHandler: function (event, validator) {
                toastr['error']("Please check the entry fields.", "Error");
            },
            highlight: function (element) {
                $(element)
                    .closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element)
                    .closest('.form-group').removeClass('has-error');
            },
            success: function (label) {
                label
                    .closest('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                var option = $(form).find("button[type=submit]:focus").attr('data');
                var id = $('#modal-edit :input[name="id"]').val();
                var priceRate = $('#modal-edit :input[name="price-rate"]').val();
                var name = $('#modal-edit :input[name="name"]').val();
                var contract = $('#modal-edit :input[name="hotel-contract-id"]').val();
                var client = $('#modal-edit :input[name="client-id"]').val();
                var active = $('#modal-edit :input[name="active"]').prop('checked') ? '1' : '0';

                $.ajax({
                    "url": "{{ route('hotel.contract.client.update') }}",
                    "type": "POST",
                    "data": {
                        id: id,
                        "price-rate": priceRate,
                        name: name,
                        "hotel-contract-id": contract,
                        "client-id": client,
                        active: active
                    },
                    "beforeSend": function() {
                        App.showMask(true, formAdd);
                    },
                    "complete": function(xhr, textStatus) {
                        App.showMask(false, formAdd);
                        if (xhr.status != '200') {
                            toastr['error']("Please check your connection and try again.", "Error on loading the content");
                        }
                        else {
                            var response = $.parseJSON(xhr.responseText);
                            if (response.status == 'success') {
                                toastr['success'](response.message, "Success");
                                needUpdate = true;
                                if (option == 'accept') {
                                    $(form).find("button.cancel-form").click();
                                }
                            }
                            else {
                                toastr['error'](response.message, "Error");
                            }
                        }
                    }
                });
            }
        });

        $('#table tbody').on( 'click', '.dt-delete', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                $.ajax({
                    url: "{{ route('hotel.contract.client.delete') }}",
                    "type": "POST",
                    "data":  {
                        id: data['id']
                    },
                    "beforeSend": function() {
                        App.showMask(true, formAdd);
                    },
                    "complete": function(xhr, textStatus) {
                        App.showMask(false, formAdd);
                        if (xhr.status != '200') {
                            toastr['error']("Please check your connection and try again.", "Error on loading the content");
                        }
                        else {
                            var response = $.parseJSON(xhr.responseText);
                            if (response.status == 'success') {
                                toastr['success'](response.message, "Success");
                                table.draw();
                            }
                            else {
                                toastr['error'](response.message, "Error");
                            }
                        }
                    }
                });
            });
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-view', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            var contract = data['contract'];
            var hotelContract = contract.hotel_contract;
            var client = contract.client;
            var priceRate = contract.price_rate;
            var hotel = hotelContract.hotel;
            var paxTypes = hotelContract.pax_types;
            var boardTypes = hotelContract.board_types;
            var roomTypes = hotelContract.room_types;
            var country = hotel.country != null ? hotel.country.name : '';
            var state = hotel.state != null ? hotel.state.name : '';
            var city = hotel.city != null ? hotel.city.name : '';
            var hotelChain = hotel.hotel_chain != null ? hotel.hotel_chain.name : '';

            var priceRateString = priceRate.market.name;
            var method = '';
            if(priceRate.type == '1')
                method = ' (+ ' + priceRate.value + '%)';
            else if(priceRate.type == '2')
                method = ' (+ $' + priceRate.value + ')';
            priceRateString += method;

            $('#modal-info :input[name=name]').val(contract.name);
            $('#modal-info :input[name=contract]').val(hotelContract.name);
            $('#modal-info :input[name=price-rate]').val(priceRateString);
            $('#modal-info :input[name=client]').val(client.name);
            $('#modal-info :input[name=valid-from]').val(moment(hotelContract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY'));
            $('#modal-info :input[name=valid-to]').val(moment(hotelContract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
            $('#modal-info :input[name=hotel]').val(hotel.name);
            $('#modal-info :input[name=country-text]').val(country);
            $('#modal-info :input[name=state-text]').val(state);
            $('#modal-info :input[name=city-text]').val(city);
            $('#modal-info :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-info :input[name=address]').val(hotel.address);
            $('#modal-info :input[name=category]').barrating('set', hotel.category);
            $('#modal-info :input[name=hotel-chain]').val(hotelChain);
            $('#modal-info :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-info :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-info :input[name=web-site]').val(hotel.web_site);
            $('#modal-info :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-info :input[name=email]').val(hotel.email);
            if (data['active'] == 1) {
                $('#modal-info :input[name=active]').prop('checked', 'checked');
                $('#modal-info :input[name=active]').val(1);
            }
            else {
                $('#modal-info :input[name=active]').prop('checked', '');
                $('#modal-info :input[name=active]').val(0);
            }

            tableInfoPaxType.api().clear();
            for (var i = 0; i < paxTypes.length; i++) {
                tableInfoPaxType.api().row.add([
                    paxTypes[i].id,
                    paxTypes[i].code + ': ' + paxTypes[i].name,
                    paxTypes[i].agefrom,
                    paxTypes[i].ageto,
                ]).draw( false );
            }
            tableInfoPaxType.api().columns.adjust().draw();

            tableInfoBoardType.api().clear();
            for (var i = 0; i < boardTypes.length; i++) {
                tableInfoBoardType.api().row.add([
                    boardTypes[i].id,
                    boardTypes[i].code + ': ' + boardTypes[i].name
                ]).draw( false );
            }
            tableInfoBoardType.api().columns.adjust().draw();

            tableInfoRoomType.api().clear();
            for (var i = 0; i < roomTypes.length; i++) {
                tableInfoRoomType.api().row.add([
                    roomTypes[i].id,
                    roomTypes[i].code + ': ' + roomTypes[i].name,
                    roomTypes[i].maxpax,
                    roomTypes[i].minpax,
                    roomTypes[i].minadult,
                    roomTypes[i].minchildren,
                    roomTypes[i].minchildren
                ]).draw( false );
            }
            tableInfoRoomType.api().columns.adjust().draw();
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-edit', function (e) {
            formEdit.validate().resetForm();
            formEdit[0].reset();
            var data = table.row( $(this).parents('tr') ).data();
            var contract = data['contract'];
            var hotelContract = contract.hotel_contract;
            var client = contract.client;
            var priceRates = hotelContract.price_rates;
            var priceRate = contract.price_rate;
            var hotel = hotelContract.hotel;
            var paxTypes = hotelContract.pax_types;
            var boardTypes = hotelContract.board_types;
            var roomTypes = hotelContract.room_types;
            var country = hotel.country != null ? hotel.country.name : '';
            var state = hotel.state != null ? hotel.state.name : '';
            var city = hotel.city != null ? hotel.city.name : '';
            var hotelChain = hotel.hotel_chain != null ? hotel.hotel_chain.name : '';

            $('#modal-edit :input[name="price-rate"]').empty();
            for (var i = 0; i < priceRates.length; i++) {
                var method = '';
                if(priceRates[i].type == '1')
                    method = '(+ ' + priceRates[i].value + '%)';
                else if(priceRates[i].type == '2')
                    method = '(+ $' + priceRates[i].value + ')';
                var option = '<option value="' + priceRates[i].id  + '">' + priceRates[i].market.name + ' ' + method + '</option>';
                $('#modal-edit :input[name="price-rate"]').append(option);
            }
            $('#modal-edit :input[name="price-rate"]').val(priceRate.id);
            $('#modal-edit :input[name=id]').val(contract.id);
            $('#modal-edit :input[name=name]').val(contract.name);
            $('#modal-edit :input[name=hotel-contract-id]').val(hotelContract.id);
            $("#modal-edit :input[name=contract]").parent().find(".select2-selection__rendered").html(hotelContract.name + '<span class="select2-selection__placeholder"></span>');
            $('#modal-edit :input[name=client-id]').val(client.id);
            $("#modal-edit :input[name=client]").parent().find(".select2-selection__rendered").html(client.name + '<span class="select2-selection__placeholder"></span>');
            $('#modal-edit :input[name=valid-from]').val(moment(hotelContract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY'));
            $('#modal-edit :input[name=valid-to]').val(moment(hotelContract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
            $('#modal-edit :input[name=hotel]').val(hotel.name);
            $('#modal-edit :input[name=country-text]').val(country);
            $('#modal-edit :input[name=state-text]').val(state);
            $('#modal-edit :input[name=city-text]').val(city);
            $('#modal-edit :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-edit :input[name=address]').val(hotel.address);
            $('#modal-edit :input[name=category]').barrating('set', hotel.category);
            $('#modal-edit :input[name=hotel-chain]').val(hotelChain);
            $('#modal-edit :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-edit :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-edit :input[name=web-site]').val(hotel.web_site);
            $('#modal-edit :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-edit :input[name=email]').val(hotel.email);
            if (data['active'] == 1) {
                $('#modal-edit :input[name=active]').prop('checked', 'checked');
                $('#modal-edit :input[name=active]').val(1);
            }
            else {
                $('#modal-edit :input[name=active]').prop('checked', '');
                $('#modal-edit :input[name=active]').val(0);
            }

            tableEditPaxType.api().clear();
            for (var i = 0; i < paxTypes.length; i++) {
                tableEditPaxType.api().row.add([
                    paxTypes[i].id,
                    paxTypes[i].code + ': ' + paxTypes[i].name,
                    paxTypes[i].agefrom,
                    paxTypes[i].ageto,
                ]).draw( false );
            }
            tableEditPaxType.api().columns.adjust().draw();

            tableEditBoardType.api().clear();
            for (var i = 0; i < boardTypes.length; i++) {
                tableEditBoardType.api().row.add([
                    boardTypes[i].id,
                    boardTypes[i].code + ': ' + boardTypes[i].name
                ]).draw( false );
            }
            tableEditBoardType.api().columns.adjust().draw();

            tableEditRoomType.api().clear();
            for (var i = 0; i < roomTypes.length; i++) {
                tableEditRoomType.api().row.add([
                    roomTypes[i].id,
                    roomTypes[i].code + ': ' + roomTypes[i].name,
                    roomTypes[i].maxpax,
                    roomTypes[i].minpax,
                    roomTypes[i].minadult,
                    roomTypes[i].minchildren,
                    roomTypes[i].minchildren
                ]).draw( false );
            }
            tableEditRoomType.api().columns.adjust().draw();
            e.preventDefault();
        });

        $('.cancel-form').on('click', function(e) {
            if(needUpdate) {
                table.draw();
                needUpdate = false;
            }
        });

        $('.btn-search-reset').on('click', function (e) {
            e.preventDefault();
            $('#search-section :input[name=name]').val('');
            $('#search-section :input[name=client]').val('');
            $('#search-section :input[name=active]').val('');
            $('#search-section :input[name=valid-from]').val('');
            $('#search-section :input[name=valid-to]').val('');
        });

        $('.btn-search-cancel').on('click', function (e) {
            e.preventDefault();
            $('#search-section').slideToggle();
        });

        $('#search-section :input[name=valid-from]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        });

        $('#search-section :input[name=valid-to]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        });

        $('.btn-search-submit').on( 'click', function (e) {
            e.preventDefault();
            table
                .columns('name:name').search($('#search-section :input[name=name]').val())
                .columns('client:name').search($('#search-section :input[name=client]').val())
                .columns('valid_from:name').search($('#search-section :input[name=valid-from]').val())
                .columns('valid_to:name').search($('#search-section :input[name=valid-to]').val())
                .columns('active:name').search($('#search-section :input[name=active]').val())
                .draw();
        });
    });
</script>
@stop