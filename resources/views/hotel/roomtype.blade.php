@extends('layouts.master2')
@section('title','Room Types')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Manage Room Types')
@section('page-sub-title','show, insert, update and delete room types')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-hotel "></i>Room Types List </div>
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
                            <th class="">Max Pax</th>
                            <th class="">Max AD</th>
                            <th class="">Min AD</th>
                            <th class="">Max CH</th>
                            <th class="">Min CH</th>
                            <th class="">Max INF</th>
                            <th class="">Min INF</th>
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
        <h4 class="modal-title"><i class="fa fa-hotel"></i> Add Room Type</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" placeholder="Code" name="code">
                </div>
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Max Pax</label>
                    <input type="text" class="form-control" placeholder="Max Pax" name="maxpax">
                </div>
                <div class="form-group">
                    <label>Max Adult</label>
                    <input type="text" class="form-control" placeholder="Max Adult" name="maxadult">
                </div>
                <div class="form-group">
                    <label>Max Children</label>
                    <input type="text" class="form-control" placeholder="Max Children" name="maxchildren">
                </div>
                <div class="form-group">
                    <label>Max Infant</label>
                    <input type="text" class="form-control" placeholder="Max Infant" name="maxinfant">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px; margin-bottom: 4px;"> Enabled
                            <input type="checkbox" value="1" name="active"/>
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Min Adult</label>
                    <input type="text" class="form-control" placeholder="Min Adult" name="minadult">
                </div>
                <div class="form-group">
                    <label>Min Children</label>
                    <input type="text" class="form-control" placeholder="Min Children" name="minchildren">
                </div>
                <div class="form-group">
                    <label>Min Infant</label>
                    <input type="text" class="form-control" placeholder="Min Infant" name="mininfant">
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
        <h4 class="modal-title"><i class="fa fa-hotel"></i> Room Type Data</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" name="code" readonly>
                </div>
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" name="name" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Max Pax</label>
                    <input type="text" class="form-control"name="maxpax" readonly>
                </div>
                <div class="form-group">
                    <label>Max Adult</label>
                    <input type="text" class="form-control" name="maxadult" readonly>
                </div>
                <div class="form-group">
                    <label>Max Children</label>
                    <input type="text" class="form-control" name="maxchildren" readonly>
                </div>
                <div class="form-group">
                    <label>Max Infant</label>
                    <input type="text" class="form-control" name="maxinfant" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px; margin-bottom: 4px;"> Enabled
                            <input type="checkbox" value="1" name="active" onclick="return false;"/>
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Min Adult</label>
                    <input type="text" class="form-control" name="minadult" readonly>
                </div>
                <div class="form-group">
                    <label>Min Children</label>
                    <input type="text" class="form-control" name="minchildren" readonly>
                </div>
                <div class="form-group">
                    <label>Min Infant</label>
                    <input type="text" class="form-control" name="mininfant" readonly>
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
        <h4 class="modal-title"><i class="fa fa-hotel"></i> Edit Room Type</h4>
    </div>
    <form id="form-edit">
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" placeholder="Code" name="code">
                </div>
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Max Pax</label>
                    <input type="text" class="form-control" placeholder="Max Pax" name="maxpax">
                </div>
                <div class="form-group">
                    <label>Max Adult</label>
                    <input type="text" class="form-control" placeholder="Max Adult" name="maxadult">
                </div>
                <div class="form-group">
                    <label>Max Children</label>
                    <input type="text" class="form-control" placeholder="Max Children" name="maxchildren">
                </div>
                <div class="form-group">
                    <label>Max Infant</label>
                    <input type="text" class="form-control" placeholder="Max Infant" name="maxinfant">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px; margin-bottom: 4px;"> Enabled
                            <input type="checkbox" value="1" name="active"/>
                            <span></span>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Min Adult</label>
                    <input type="text" class="form-control" placeholder="Min Adult" name="minadult">
                </div>
                <div class="form-group">
                    <label>Min Children</label>
                    <input type="text" class="form-control" placeholder="Min Children" name="minchildren">
                </div>
                <div class="form-group">
                    <label>Min Infant</label>
                    <input type="text" class="form-control" placeholder="Min Infant" name="mininfant">
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
<script>
    var routeRead = "{{ route('hotel.roomtype.read') }}";
    var routeCreate = "{{ route('hotel.roomtype.create') }}";
    var routeUpdate = "{{ route('hotel.roomtype.update') }}";
    var routeDelete = "{{ route('hotel.roomtype.delete') }}";
    var routeDuplicate = "{{ route('hotel.roomtype.duplicate') }}";
    var routeExcel = "{{ route('hotel.roomtype.excel') }}";
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/hotel/roomtype.min.js') }}" type="text/javascript"></script>
@stop