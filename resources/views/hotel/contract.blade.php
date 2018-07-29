@extends('layouts.master2')
@section('title','Contracts')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
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
                        <a class="btn btn-circle btn-icon-only btn-default dropdown-toggle lenght btn-dropdown" data-toggle="dropdown" href="javascript:;">25</a>
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
                                        <i class="fa fa-building-o"></i>
                                        <input type="text" class="form-control" name="hotel" placeholder="Hotel"> </div>
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
                            <!--div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon left">
                                        <i class="fa fa-check"></i>
                                        <select class="form-control" name="active">
                                            <option value="">Enabled/Disabled</option>
                                            <option value="1">Enabled</option>
                                            <option value="0">Disabled</option>
                                        </select> </div>
                                </div>
                            </div-->
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
                            <th class="">Hotel</th>
                            <th class="">Valid From</th>
                            <th class="">Valid To</th>
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
                                <div class="form-group">
                                    <label class="control-label">Valid From</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" placeholder="Valid From" type="text" value="" name="valid-from"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <div class="input-icon left">
                                        <i class="icon-note"></i>
                                        <select class="form-control" name="status">
                                            <option value="">Select Status</option>
                                            <option value="0">Draft</option>
                                            <option value="1">Signed</option>
                                        </select> </div>
                               </div>
                                <div class="form-group">
                                    <label class="control-label">Valid To</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" placeholder="Valid From" type="text" value="" name="valid-to"/>
                                    </div>
                                </div>
                                <!--div class="form-group">
                                    <div class="mt-checkbox-list">
                                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                                            <input type="checkbox" value="1" name="active"/>
                                            <span></span>
                                        </label>
                                    </div>
                                </div-->
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
                                    <div class="input-group">
                                        <select class="form-control select-hotel" name="hotel"></select>
                                    </div>
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
                                        <th>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set=".table-pax-type .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th> Id </th>
                                        <th> Pax Type </th>
                                        <th> From </th>
                                        <th> To </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($paxTypes as $p)
                                    <tr class="odd gradeX">
                                        <td>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="checkboxes" value="1" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td> {{ $p->id  }} </td>
                                        <td> {{ $p->code . ': ' . $p->name }} </td>
                                        <td> {{ $p->agefrom }} </td>
                                        <td> {{ $p->ageto }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="control-label">Search Room Type</label>
                                <div class="input-group input-group-md select2-bootstrap-append">
                                    <select class="form-control js-data-ajax" multiple></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="count-room-type" class="hotel-type" value="0">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0" style="margin-top: 33px !important;">
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
                            <i class="icon-note"></i> Board Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <input type="hidden" class="hotel-type" name="count-board-type" value="0">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-board-type" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set=".table-board-type .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th> Id </th>
                                        <th> Board Type </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($boardTypes as $b)
                                    <tr class="odd gradeX">
                                        <td>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="checkboxes" value="1" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td> {{ $b->id }} </td>
                                        <td> {{ $b->code . ': ' . $b->name }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
                                <div class="form-group">
                                    <label class="control-label">Valid From</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control" type="text" value="" name="valid-from" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <div class="input-icon left">
                                        <i class="icon-note"></i>
                                        <input class="form-control" type="text" value="" name="status" readonly/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Valid To</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control" type="text" value="" name="valid-to" readonly/>
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
                                    <label>Hotel</label>
                                    <input type="text" class="form-control" name="hotel" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control" name="country-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="state-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city-text" readonly>
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
                            <i class="icon-note"></i> Board Types </div>
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
                                <div class="form-group">
                                    <label class="control-label">Valid From</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" placeholder="Valid From" type="text" value="" name="valid-from"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Status</label>
                                    <div class="input-icon left">
                                        <i class="icon-note"></i>
                                        <select class="form-control" name="status">
                                            <option value="">Select Status</option>
                                            <option value="0">Draft</option>
                                            <option value="1">Signed</option>
                                        </select> </div>
                               </div>
                                <div class="form-group">
                                    <label class="control-label">Valid To</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" placeholder="Valid From" type="text" value="" name="valid-to"/>
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
                        <input type="hidden" name="id">
                        <input type="hidden" name="hotel-id">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group parent-select-hotel">
                                    <label class="control-label">Hotel</label>
                                    <div class="input-group">
                                        <select class="form-control select-hotel" name="hotel"></select>
                                    </div>
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
                                        <th>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set=".table-pax-type .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th> Id </th>
                                        <th> Pax Type </th>
                                        <th> From </th>
                                        <th> To </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($paxTypes as $p)
                                    <tr class="odd gradeX">
                                        <td>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="checkboxes" value="1" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td> {{ $p->id  }} </td>
                                        <td> {{ $p->code . ': ' . $p->name }} </td>
                                        <td> {{ $p->agefrom }} </td>
                                        <td> {{ $p->ageto }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 parent-select-room"></div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="count-room-type" class="hotel-type" value="0">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0" style="margin-top: 33px !important;">
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
                            <i class="icon-note"></i> Board Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <input type="hidden" class="hotel-type" name="count-board-type" value="0">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-board-type" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="group-checkable" data-set=".table-board-type .checkboxes" />
                                                <span></span>
                                            </label>
                                        </th>
                                        <th> Id </th>
                                        <th> Board Type </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($boardTypes as $b)
                                    <tr class="odd gradeX">
                                        <td>
                                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                <input type="checkbox" class="checkboxes" value="1" />
                                                <span></span>
                                            </label>
                                        </td>
                                        <td> {{ $b->id }} </td>
                                        <td> {{ $b->code . ': ' . $b->name }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
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
<script src="{{ asset('assets/pages/scripts/table-datatables-responsive.min.js') }}" type="text/javascript"></script>
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
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
        var needUpdate = false;

        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $('#search-section :input[name=valid-from]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $('#search-section :input[name=valid-to]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd-mm-yyyy'
        });

        $('.hotel-category').barrating({
            theme: 'fontawesome-stars'
        });

        $('#modal-add .toggle-hotel').on('click', function() {
            $('#modal-add .show-hotel').slideToggle();
        });

        $('#modal-add :input[name=category]').barrating('readonly', true);
        $('#modal-info :input[name=category]').barrating('readonly', true);
        $('#modal-edit :input[name=category]').barrating('readonly', true);

        function countSelectedRecords(table) {
            var selected = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).size();
            return selected;
        }

        function getSelectedRows(table) {
            var rows = [];
            $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).each(function() {
                var data = table.api().row( $(this).parents('tr') ).data();
                rows.push(data[1]);
            });
            return rows;
        }

        function desactiveRows(table) {
            $('tbody > tr', table).each(function() {
                $(this).removeClass('active');
            });
        }

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
            "iDisplayLength" : 25,
            "ajax": {
                "url": "{{ route('hotel.contract.read') }}",
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
                {data: 'id', name: 'id', visible: false},
                {data: 'name', name: 'name'},
                {data: 'hotel', name: 'hotel'},
                {data: 'valid_from', name: 'valid_from'},
                {data: 'valid_to', name: 'valid_to'},
                {
                    data: 'active',
                    name: 'active',
                    visible: false,
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
                        var data = '<div class="dt-actions">' +
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info">' +
                            '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>'+
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-edit" data-toggle="modal" href="#modal-edit">' +
                            '<i class="icon-pencil btn-action-icon"></i></a>' +
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-delete" href="javascript:;" data-popout="true" data-placement="left"' +
                            'data-btn-ok-label="Yes" data-btn-ok-class="btn-sm btn-success"  data-btn-ok-icon-content="check" ' +
                            'data-btn-cancel-label="No" data-btn-cancel-class="btn-sm btn-danger" data-btn-cancel-icon-content="close" data-title="Are you sure?" data-content="">' +
                            '<i class="icon-trash btn-action-icon"></i></a>';
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
            tableAddRoomType.api().clear().draw();
            $('#modal-add .js-data-ajax').val(null).trigger('change');
            $('#modal-add .select-hotel').val(null).trigger('change');
            desactiveRows(tableAddBoardType);
            desactiveRows(tableAddPaxType);
        });

        $('.lenght-option').on('click', function () {
            var value = $(this).attr('data');
            $(this).parent().parent().prev('a').text(value);
            var select = $('select[name=table_length]');
            select.val(value);
            select.change();
        });

        $.validator.addMethod('greaterThanZero', function (value, element, param) {
            return this.optional(element) || parseInt(value) > 0;
        }, 'At least one element is required.');

        jQuery.validator.addMethod("validDate", function(value, element) {
            return this.optional(element) || moment(value,"DD-MM-YYYY",true).isValid();
        }, "Invalid date, use dd-mm-yyyy.");

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
                hotel: {
                    required: true
                },
                status: {
                    required: true
                },
                "valid-from": {
                    required: true,
                    validDate: true
                },
                "valid-to": {
                    required: true,
                    validDate: true
                },
                "count-board-type": {
                    greaterThanZero: true
                },
                "count-pax-type": {
                    greaterThanZero: true
                },
                "count-room-type": {
                    greaterThanZero: true
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
                }else if (element.hasClass("hotel-category")) {
                    error.insertAfter(element.next());
                }else if (element.hasClass("hotel-type")) {
                    error.insertAfter(element.next());
                }else if (element.hasClass("select-hotel")) {
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
                var formData = new FormData(formAdd[0]);
                formData.append('paxTypes', JSON.stringify(getSelectedRows(tableAddPaxType)));
                formData.append('boardTypes', JSON.stringify(getSelectedRows(tableAddBoardType)));
                formData.append('roomTypes', JSON.stringify($('#modal-add .js-data-ajax').val()));
                $.ajax({
                    "url": "{{ route('hotel.contract.create') }}",
                    "type": "POST",
                    "data": formData,
                    "contentType": false,
                    "processData": false,
                    //"data": formAdd.serialize(),
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
                                formAdd[0].reset();
                                needUpdate = true;
                                if (option == 'accept') {
                                    $(form).find("button.cancel-form").click();
                                }
                                tableAddRoomType.api().clear().draw();
                                $('#modal-add .js-data-ajax').val(null).trigger('change');
                                $('#modal-add .select-hotel').val(null).trigger('change');
                                desactiveRows(tableAddBoardType);
                                desactiveRows(tableAddPaxType);
                            }
                            else {
                                toastr['error'](response.message, "Error");
                            }
                        }
                    }
                });
            }
        });

        $('.mt-checkbox').change(function () {
            var checkbox = $('.mt-checkbox > input[type=checkbox]');
            if (checkbox.is(':checked'))
                $('.mt-checkbox > input[type=checkbox]').val(1);
            else
                $('.mt-checkbox > input[type=checkbox]').val(0);
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
                "hotel-id": {
                    required: true
                },
                status: {
                    required: true
                },
                "valid-from": {
                    required: true,
                    validDate: true
                },
                "valid-to": {
                    required: true,
                    validDate: true
                },
                "count-board-type": {
                    greaterThanZero: true
                },
                "count-pax-type": {
                    greaterThanZero: true
                },
                "count-room-type": {
                    greaterThanZero: true
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
                } else if (element.hasClass("hotel-category")) {
                    error.insertAfter(element.next());
                }else if (element.hasClass("hotel-type")) {
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
                var formData = new FormData(formEdit[0]);
                formData.append('paxTypes', JSON.stringify(getSelectedRows(tableEditPaxType)));
                formData.append('boardTypes', JSON.stringify(getSelectedRows(tableEditBoardType)));
                formData.append('roomTypes', JSON.stringify($('#modal-edit .js-data-ajax').val()));
                $.ajax({
                    "url": "{{ route('hotel.contract.update') }}",
                    "type": "POST",
                    //"data": formEdit.serialize(),
                    "data": formData,
                    "contentType": false,
                    "processData": false,
                    "beforeSend": function() {
                        App.showMask(true, formEdit);
                    },
                    "complete": function(xhr, textStatus) {
                        App.showMask(false, formEdit);
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
                            desactiveRows(tableEditBoardType);
                            desactiveRows(tableEditPaxType);
                        }
                    }
                });
            }
        });

        $('#table tbody').on( 'click', '.dt-view', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            var contract = data['contract'];
            var hotel = contract.hotel;
            var paxTypes = contract.pax_types;
            var boardTypes = contract.board_types;
            var roomTypes = contract.room_types;

            $('#modal-info :input[name=name]').val(contract.name);
            $('#modal-info :input[name=status]').val(contract.status_text);
            $('#modal-info :input[name=valid-from]').val(moment(contract.valid_from, 'YYYY-MM-DD').format('DD-MM-YYYY'));
            $('#modal-info :input[name=valid-to]').val(moment(contract.valid_to, 'YYYY-MM-DD').format('DD-MM-YYYY'));
            $('#modal-info :input[name=hotel]').val(hotel.name);
            $('#modal-info :input[name=country-text]').val(hotel.country.name);
            $('#modal-info :input[name=state-text]').val(hotel.state.name);
            $('#modal-info :input[name=city-text]').val(hotel.city.name);
            $('#modal-info :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-info :input[name=address]').val(hotel.address);
            $('#modal-info :input[name=category]').barrating('set', hotel.category);
            $('#modal-info :input[name=hotel-chain]').val(hotel.hotel_chain.name);
            $('#modal-info :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-info :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-info :input[name=web-site]').val(hotel.web_site);
            $('#modal-info :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-info :input[name=email]').val(hotel.email);

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

        $("#modal-edit :input[name=hotel]").select2({
            width: "off",
            ajax: {
                url: "{{ route('hotel.search.active') }}",
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
            templateResult: formatHotel,
            templateSelection: formatHotelSelection
        });

        $('#modal-edit :input[name=hotel]').on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            if(values.selected) {
                $('#modal-edit .show-hotel :input[name=hotel-id]').val(values.id);
                $('#modal-edit .show-hotel :input[name=name]').val(values.name);
                $('#modal-edit .show-hotel :input[name=country-text]').val(values.country.name);
                $('#modal-edit .show-hotel :input[name=state-text]').val(values.state.name);
                $('#modal-edit .show-hotel :input[name=city-text]').val(values.city.name);
                $('#modal-edit .show-hotel :input[name=postal-code]').val(values.postal_code);
                $('#modal-edit .show-hotel :input[name=address]').val(values.address);
                $('#modal-edit .show-hotel :input[name=category]').barrating('readonly', true);
                var category = values.category != null ? values.category : '';
                $('#modal-edit .show-hotel :input[name=category]').barrating('set', category);
                $('#modal-edit .show-hotel :input[name=hotel-chain]').val(values.hotel_chain.name);
                $('#modal-edit .show-hotel :input[name=admin-phone]').val(values.admin_phone);
                $('#modal-edit .show-hotel :input[name=admin-fax]').val(values.admin_fax);
                $('#modal-edit .show-hotel :input[name=web-site]').val(values.web_site);
                $('#modal-edit .show-hotel :input[name=turistic-licence]').val(values.turistic_licence);
                $('#modal-edit .show-hotel :input[name=email]').val(values.email);
            }
        });

        $('#table tbody').on( 'click', '.dt-edit', function (e) {
            $('#modal-edit .parent-select-room').html('');
            desactiveRows(tableEditBoardType);
            desactiveRows(tableEditPaxType);
            formEdit.validate().resetForm();
            formEdit[0].reset();
            var data = table.row( $(this).parents('tr') ).data();
            var contract = data['contract'];
            var hotel = contract.hotel;
            var paxTypes = contract.pax_types;
            var boardTypes = contract.board_types;
            var roomTypes = contract.room_types;

            $('#modal-edit :input[name=id]').val(contract.id);
            $('#modal-edit :input[name=hotel-id]').val(hotel.id);

            $("#modal-edit .select2-selection__rendered").html(hotel.name);
            $('#modal-edit :input[name=name]').val(contract.name);
            $('#modal-edit :input[name=status]').val(contract.status);
            $('#modal-edit :input[name=valid-from]').datepicker("setDate" , new Date(moment(contract.valid_from, 'YYYY-MM-DD')));
            $('#modal-edit :input[name=valid-to]').datepicker("setDate" , new Date(moment(contract.valid_to, 'YYYY-MM-DD')));
            $('#modal-edit :input[name=country-text]').val(hotel.country.name);
            $('#modal-edit :input[name=state-text]').val(hotel.state.name);
            $('#modal-edit :input[name=city-text]').val(hotel.city.name);
            $('#modal-edit :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-edit :input[name=address]').val(hotel.address);
            $('#modal-edit :input[name=category]').barrating('set', hotel.category);
            $('#modal-edit :input[name=hotel-chain]').val(hotel.hotel_chain.name);
            $('#modal-edit :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-edit :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-edit :input[name=web-site]').val(hotel.web_site);
            $('#modal-edit :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-edit :input[name=email]').val(hotel.email);

            for (var i = 0; i < paxTypes.length; i++) {
                $('tbody > tr > td:nth-child(1) input[type="checkbox"]', tableEditPaxType).each(function() {
                    var data = tableEditPaxType.api().row( $(this).parents('tr') ).data();
                    if (data[1] == paxTypes[i].id) {
                        $(this).prop('checked', 'checked');
                    }
                });
            }
            $('#modal-edit :input[name=count-pax-type]').val(countSelectedRecords(tableEditPaxType));
            tableEditPaxType.api().columns.adjust().draw();

            for (var i = 0; i < boardTypes.length; i++) {
                $('tbody > tr > td:nth-child(1) input[type="checkbox"]', tableEditBoardType).each(function() {
                    var data = tableEditBoardType.api().row( $(this).parents('tr') ).data();
                    if (data[1] == boardTypes[i].id) {
                        $(this).prop('checked', 'checked');
                    }
                });
            }

            $('#modal-edit :input[name=count-board-type]').val(countSelectedRecords(tableEditBoardType));
            tableEditBoardType.api().columns.adjust().draw();

            var selected = [];
            var initials = [];
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

                initials.push({id: roomTypes[i].id, code: roomTypes[i].code, name: roomTypes[i].name});
                selected.push(roomTypes[i].id);
            }
            $('#modal-edit :input[name=count-room-type]').val(roomTypes.length);
            tableEditRoomType.api().columns.adjust().draw();

            var htmlSelectRoom =
                '<label class="control-label">Search Room Type</label>' +
                '<div class="input-group input-group-md select2-bootstrap-append">' +
                    '<select class="form-control js-data-ajax" multiple></select>' +
                '</div>';

            $('#modal-edit .parent-select-room').append(htmlSelectRoom);

            $("#modal-edit .js-data-ajax").select2({
                data: initials,
                width: "off",
                ajax: {
                    url: "{{ route('hotel.roomtype.search.active') }}",
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
                templateResult: formatRepo,
                templateSelection: formatRepoSelection
            });

            $('#modal-edit .js-data-ajax').on('select2:select select2:unselect', function (e) {
                var values = e.params.data;
                //console.log(values);
                if(values.selected) {
                    tableEditRoomType.api().row.add([
                        values.id,
                        values.code + ': ' + values.name,
                        values.maxpax,
                        values.minpax,
                        values.minadult,
                        values.minchildren,
                        values.minchildren
                    ]).draw( false );
                    tableEditRoomType.api().columns.adjust().draw();
                }
                else {
                    var onTanble = false;
                    var array = $(this).val();
                    if (array != null) {
                        for (var i = 0; i < array.length; i++) {
                            if (array[i] == values.id) {
                                onTanble = true;
                                break;
                            }
                        }
                    }
                    if (!onTanble) {
                        tableEditRoomType
                            .api().rows( function ( idx, data, node ) {
                                return data[0] == values.id;
                            } )
                            .remove()
                            .draw();
                    }
                }
                $('#modal-edit :input[name=count-room-type]').val(tableEditRoomType.api().rows().count());
            });
            $("#modal-edit .js-data-ajax").val(selected).trigger('change');
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-delete', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                $.ajax({
                    url: "{{ route('hotel.contract.delete') }}",
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

        $('.cancel-form').on('click', function(e) {
            if(needUpdate) {
                table.draw();
                needUpdate = false;
            }
        });

        $('.btn-search-reset').on('click', function (e) {
            e.preventDefault();
            $('#search-section :input[name=name]').val('');
            $('#search-section :input[name=hotel]').val('');
            $('#search-section :input[name=active]').val('');
            $('#search-section :input[name=valid-from]').val('');
            $('#search-section :input[name=valid-to]').val('');
        });

        $('.btn-search-cancel').on('click', function (e) {
            e.preventDefault();
            $('#search-section').slideToggle();
        });

        $('.btn-search-submit').on( 'click', function (e) {
            e.preventDefault();
            table
                .columns('name:name').search($('#search-section :input[name=name]').val())
                .columns('hotel:name').search($('#search-section :input[name=hotel]').val())
                .columns('valid_from:name').search($('#search-section :input[name=valid-from]').val())
                .columns('valid_to:name').search($('#search-section :input[name=valid-to]').val())
                .columns('active:name').search($('#search-section :input[name=active]').val())
            .draw();
        });

        var tableAddPaxType = $('#modal-add .table-pax-type').dataTable({
            "sDom": "t",
            "autoWidth": false,
            "columnDefs": [
                { 'orderable': false, "className": "dt-center", 'targets': [0], "width": "20%" },
                { 'visible': false, 'targets': [1] }
            ],
            "order": [[ 3, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        tableAddPaxType.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            $('#modal-add :input[name=count-pax-type]').val(countSelectedRecords(tableAddPaxType));
        });

        tableAddPaxType.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
            $('#modal-add :input[name=count-pax-type]').val(countSelectedRecords(tableAddPaxType));
        });

        var tableAddBoardType = $('#modal-add .table-board-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'orderable': false, "className": "dt-center", 'targets': [0], "width": "20%" },
                { 'visible': false, 'targets': [1] }
            ],
            "order": [[ 2, "asc" ]],
            "autoWidth": false,
            "lengthMenu": [[-1], ["All"]]
        });

        tableAddBoardType.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            $('#modal-add :input[name=count-board-type]').val(countSelectedRecords(tableAddBoardType));
        });

        tableAddBoardType.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
            $('#modal-add :input[name=count-board-type]').val(countSelectedRecords(tableAddBoardType));
        });

        var tableAddRoomType = $('#modal-add .table-room-type').dataTable({
            "sDom": "t",
            "lengthMenu": [[-1], ["All"]],
            "ordering": false,
            "autoWidth": false,
            "columnDefs": [
                { 'visible': false, 'targets': [0] },
                { 'targets': [1], width: '35%' }
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

        var tableEditPaxType = $('#modal-edit .table-pax-type').dataTable({
            "sDom": "t",
            "autoWidth": false,
            "columnDefs": [
                { 'orderable': false, "className": "dt-center", 'targets': [0], "width": "20%" },
                { 'visible': false, 'targets': [1] }
            ],
            "order": [[ 3, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        tableEditPaxType.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            $('#modal-edit :input[name=count-pax-type]').val(countSelectedRecords(tableEditPaxType));
        });

        tableEditPaxType.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
            $('#modal-edit :input[name=count-pax-type]').val(countSelectedRecords(tableEditPaxType));
        });

        var tableEditBoardType = $('#modal-edit .table-board-type').dataTable({
            "sDom": "t",
            "autoWidth": false,
            "columnDefs": [
                { 'orderable': false, "className": "dt-center", 'targets': [0], "width": "20%" },
                { 'visible': false, 'targets': [1] }
            ],
            "order": [[ 2, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        tableEditBoardType.find('.group-checkable').change(function () {
            var set = jQuery(this).attr("data-set");
            var checked = jQuery(this).is(":checked");
            jQuery(set).each(function () {
                if (checked) {
                    $(this).prop("checked", true);
                    $(this).parents('tr').addClass("active");
                } else {
                    $(this).prop("checked", false);
                    $(this).parents('tr').removeClass("active");
                }
            });
            $('#modal-edit :input[name=count-board-type]').val(countSelectedRecords(tableEditBoardType));
        });

        tableEditBoardType.on('change', 'tbody tr .checkboxes', function () {
            $(this).parents('tr').toggleClass("active");
            $('#modal-edit :input[name=count-board-type]').val(countSelectedRecords(tableEditBoardType));
        });

        var tableEditRoomType = $('#modal-edit .table-room-type').dataTable({
            "sDom": "t",
            "lengthMenu": [[-1], ["All"]],
            "ordering": false,
            "autoWidth": false,
            "columnDefs": [
                { 'visible': false, 'targets': [0] },
                { 'width': '35%', 'targets': [1] }
            ],
            "language": {
                "emptyTable": "No room type selected"
            }
        });

        function formatRepo(repo) {
            if (repo.loading) return repo.text;
            var markup =
                "<div class=''>" +
                    "<div class=''>" + repo.code + ": " + repo.name + "</div>"+
                "</div>";
            return markup;
        }

        function formatRepoSelection(repo) {
            return repo.code + ": " + repo.name;
        }

        function formatHotel(repo) {
            if (repo.loading) return repo.text;
            var markup =
                "<div class=''>" +
                    "<div class=''>" + repo.name + "</div>"+
                "</div>";
            return markup;
        }

        function formatHotelSelection(repo) {
            return repo.name;
        }

        $("#modal-add :input[name=hotel]").select2({
            width: "off",
            ajax: {
                url: "{{ route('hotel.search.active') }}",
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
            templateResult: formatHotel,
            templateSelection: formatHotelSelection
        });

        $('#modal-add :input[name=hotel]').on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            if(values.selected) {
                $('#modal-add .show-hotel :input[name=hotel-id]').val(values.id);
                $('#modal-add .show-hotel :input[name=name]').val(values.name);
                $('#modal-add .show-hotel :input[name=country-text]').val(values.country.name);
                $('#modal-add .show-hotel :input[name=state-text]').val(values.state.name);
                $('#modal-add .show-hotel :input[name=city-text]').val(values.city.name);
                $('#modal-add .show-hotel :input[name=postal-code]').val(values.postal_code);
                $('#modal-add .show-hotel :input[name=address]').val(values.address);
                $('#modal-add .show-hotel :input[name=category]').barrating('readonly', true);
                var category = values.category != null ? values.category : '';
                $('#modal-add .show-hotel :input[name=category]').barrating('set', category);
                $('#modal-add .show-hotel :input[name=hotel-chain]').val(values.hotel_chain.name);
                $('#modal-add .show-hotel :input[name=admin-phone]').val(values.admin_phone);
                $('#modal-add .show-hotel :input[name=admin-fax]').val(values.admin_fax);
                $('#modal-add .show-hotel :input[name=web-site]').val(values.web_site);
                $('#modal-add .show-hotel :input[name=turistic-licence]').val(values.turistic_licence);
                $('#modal-add .show-hotel :input[name=email]').val(values.email);
            }
        });

        $("#modal-add .js-data-ajax").select2({
            width: "off",
            ajax: {
                url: "{{ route('hotel.roomtype.search.active') }}",
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
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        $('#modal-add .js-data-ajax').on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            if(values.selected) {
                tableAddRoomType.api().row.add([
                    values.id,
                    values.code + ': ' + values.name,
                    values.maxpax,
                    values.minpax,
                    values.minadult,
                    values.minchildren,
                    values.minchildren
                ]).draw( false );
                tableAddRoomType.api().columns.adjust().draw();
            }
            else {
                var onTanble = false;
                var array = $(this).val();
                if (array != null) {
                    for (var i = 0; i < array.length; i++) {
                        if (array[i] == values.id) {
                            onTanble = true;
                            break;
                        }
                    }
                }
                if (!onTanble) {
                    tableAddRoomType
                        .api().rows( function ( idx, data, node ) {
                            return data[0] == values.id;
                        } )
                        .remove()
                        .draw();
                }
            }
            $('#modal-add :input[name=count-room-type]').val(tableAddRoomType.api().rows().count());
        });

        $("button[data-select2-open]").click(function() {
            $("#" + $(this).data("select2-open")).select2("open");
        });
    });
</script>
@stop