@extends('layouts.master2')
@section('title','Board Types')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Manage Board Types')
@section('page-sub-title','show, insert, update and delete board types')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cutlery"></i>Board Types List </div>
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
                            <th class="">Description</th>
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
        <h4 class="modal-title"><i class="fa fa-cutlery"></i> Add Board Type</h4>
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
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Description" style="resize: none;"></textarea>
                </div>
                <div class="form-group no-margin-bottom">
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
        <h4 class="modal-title"><i class="fa fa-cutlery"></i> Board Type Data</h4>
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
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="3" style="resize: none;" readonly onclick="return false;"></textarea>
                </div>
                <div class="form-group no-margin-bottom">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                            <input type="checkbox" value="1" name="active" readonly onclick="return false;"/>
                            <span></span>
                        </label>
                    </div>
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
        <h4 class="modal-title"><i class="fa fa-cutlery"></i> Edit Board Type</h4>
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
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Description" style="resize: none;"></textarea>
                </div>
                <div class="form-group no-margin-bottom">
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
<script>
    var routeRead = "{{ route('hotel.boardtype.read') }}";
    var routeCreate = "{{ route('hotel.boardtype.create') }}";
    var routeUpdate = "{{ route('hotel.boardtype.update') }}";
    var routeDelete = "{{ route('hotel.boardtype.delete') }}";
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/hotel/boardtype.min.js') }}" type="text/javascript"></script>
@stop