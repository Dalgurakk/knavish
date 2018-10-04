@extends('layouts.master2')
@section('title','Locations')
@section('page-css')
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jstree2/dist/themes/default/style.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-iconpicker/dist/css/bootstrap-iconpicker.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Manage Locations')
@section('page-sub-title','show, insert, update and delete locations')

@section('content')
<div class="row" id="div-content">
    <div id="tree-container" class="col-md-8 col-sm-8 col-xs-12" data-expanded="false">
        <div class="portlet light custom-container porlet-tree">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe "></i>Locations Tree</div>
                <div class="actions">
                    <div class="btn-group">
                        <a class="btn btn-circle btn-icon-only btn-default dropdown-toggle btn-dropdown add" data-toggle="dropdown" href="javascript:;">
                            <i class="fa fa-plus"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-options">
                            <li>
                                <a class="node-option" data="node" href="">Node</a>
                            </li>
                            <li>
                                <a class="node-option" data="root" href="">Root</a>
                            </li>
                        </ul>
                    </div>
                    <!--a class="btn btn-circle btn-icon-only btn-default expand hide-in-small" data-toggle="expand" href="javascript:;">
                        <i class="fa fa-arrows-h"></i>
                    </a-->
                </div>
            </div>
            <div class="portlet-body">
                <div id="tree"> </div>
            </div>
        </div>
    </div>
    <div id="node-data" class="col-md-4 col-sm-4 col-xs-12">
        <div class="portlet light custom-container porlet-data">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-globe "></i>Location Selected</div>
                <div class="actions"></div>
            </div>
            <div class="portlet-body">
                <div class="row node-data">
                    <input name="parent-id" type="hidden" value="0">
                    <input name="id" type="hidden" value="">
                    <div class="col-md-9 col-sm-9">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" placeholder="" name="code" readonly>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-3">
                        <div class="form-group">
                            <label>Icon</label>
                            <div name="icon" class="form-control icon-picker show-icon" style="background-color: #eef1f5;">
                                <i class="glyphicon glyphicon-question-sign" style="top: 9px;"></i></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Denomination</label>
                            <input type="text" class="form-control" placeholder="" name="name" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
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
                <hr style="margin-top: 10px; margin-bottom: 15px;">
                <div style="text-align: right;">
                    <button class="btn green edit-node disabled"><i class="icon-pencil"></i> Edit</button>
                    <button class="btn red delete-node disabled"><i class="icon-trash"></i> Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal-add" class="modal fade custom-container" tabindex="-1" data-width="420" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-globe"></i> Add Location</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <div class="row">
            <input name="parent-id" type="hidden" value="0">
            <input name="icon" type="hidden" value="glyphicon-globe">
            <div class="col-md-9 col-sm-9">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" placeholder="Code" name="code">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group no-margin-bottom">
                    <label>Icon</label>
                    <button class="btn btn-default form-control icon-picker"></button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
            </div>
            <div class="col-md-4">
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

<div id="modal-edit" class="modal fade custom-container" tabindex="-1" data-width="420" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-globe"></i> Edit Location</h4>
    </div>
    <form id="form-edit">
    <div class="modal-body">
        <div class="row">
            <input name="id" type="hidden" value="0">
            <input name="icon" type="hidden" value="glyphicon-globe">
            <div class="col-md-9 col-sm-9">
                <div class="form-group">
                    <label>Code</label>
                    <input type="text" class="form-control" placeholder="Code" name="code">
                </div>
            </div>
            <div class="col-md-3 col-sm-3">
                <div class="form-group no-margin-bottom">
                    <label>Icon</label>
                    <button class="btn btn-default form-control icon-picker"></button>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
            </div>
            <div class="col-md-4">
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

<!--div id="modal-confirmation" class="modal fade custom-container" tabindex="-1" data-width="450" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-globe"></i> Confirmation</h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label>Are you sure you want to delete the location?</label>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green confirm-delete" data="accept"><i class="fa fa-check"></i> Accept</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div-->
@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jstree2/dist/jstree.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-iconpicker/dist/js/bootstrap-iconpicker-iconset-all.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-iconpicker/dist/js/bootstrap-iconpicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
<script>
    var routeRead = "{{ route('administration.location.read') }}";
    var routeCreate = "{{ route('administration.location.create') }}";
    var routeUpdate = "{{ route('administration.location.update') }}";
    var routeDelete = "{{ route('administration.location.delete') }}";
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/administration/location.min.js') }}" type="text/javascript"></script>
@stop