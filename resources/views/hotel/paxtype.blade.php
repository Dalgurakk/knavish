@extends('layouts.master2')
@section('title','Pax Types')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Manage Pax Types')
@section('page-sub-title','show, insert, update and delete pax types')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-male "></i>Pax Types List </div>
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
                    <a class="btn btn-circle btn-icon-only btn-default excel" href="javascript:;">
                        <i class="fa fa-file-excel-o"></i>
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
                                    <input type="text" class="form-control" name="code" placeholder="Code">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Denomination">
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
                            <th class="">Code</th>
                            <th class="">Denomination</th>
                            <th class="">Type</th>
                            <th class="">Age From</th>
                            <th class="">Age To</th>
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

<div id="modal-add" class="modal fade custom-container" tabindex="-1" data-width="550" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-male"></i> Add Pax Type</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" placeholder="Code" name="code">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        <option value="1">Infant</option>
                        <option value="2">Children</option>
                        <option value="3">Adult</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group no-margin-bottom">
                    <div class="mt-checkbox-list margin-top-15">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                            <input type="checkbox" value="1" name="active"/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Age From</label>
                    <input type="text" class="form-control" placeholder="Age From" name="agefrom">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Age To</label>
                    <input type="text" class="form-control" placeholder="Age To" name="ageto">
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

<div id="modal-info" class="modal fade custom-container" tabindex="-1" data-width="550" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-male"></i> Pax Type Data</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" name="code" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" name="name" readonly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Type</label>
                    <input type="text" class="form-control" name="type" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group no-margin-bottom">
                    <div class="mt-checkbox-list margin-top-15">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                            <input type="checkbox" value="1" name="active" onclick="return false;"/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Age From</label>
                    <input type="text" class="form-control" name="agefrom" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Age To</label>
                    <input type="text" class="form-control" name="ageto" readonly>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>

<div id="modal-edit" class="modal fade custom-container" tabindex="-1" data-width="550" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-male"></i> Edit Pax Type</h4>
    </div>
    <form id="form-edit">
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" placeholder="Code" name="code">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        <option value="1">Infant</option>
                        <option value="2">Children</option>
                        <option value="3">Adult</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group no-margin-bottom">
                    <div class="mt-checkbox-list margin-top-15">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                            <input type="checkbox" value="1" name="active"/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Age From</label>
                    <input type="text" class="form-control" placeholder="Age From" name="agefrom">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Age To</label>
                    <input type="text" class="form-control" placeholder="Age To" name="ageto">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
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
<script src="{{ asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
<script>
    var routeRead = "{{ route('hotel.paxtype.read') }}";
    var routeCreate = "{{ route('hotel.paxtype.create') }}";
    var routeUpdate = "{{ route('hotel.paxtype.update') }}";
    var routeDelete = "{{ route('hotel.paxtype.delete') }}";
    var routeExcel = "{{ route('hotel.paxtype.excel') }}";
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/hotel/paxtype.min.js') }}" type="text/javascript"></script>
@stop