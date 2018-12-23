@extends('layouts.master2')
@section('title','Traces')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Traces')
@section('page-sub-title','check all data changes')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-check "></i>Traces List </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default search" href="javascript:;">
                        <i class="fa fa-search"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default reload" href="javascript:;">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <!--a class="btn btn-circle btn-icon-only btn-default excel" href="javascript:;">
                        <i class="fa fa-file-excel-o"></i>
                    </a-->
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
                <div id="search-section" class="search-section">
                    <form id="form-search">
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="user-id" placeholder="User Id">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="username" placeholder="Username">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="email" placeholder="Email">
                                </div>
                            </div>
                            <!--div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Name">
                                </div>
                            </div-->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <select class="form-control" name="event">
                                        <option value="">Select an Event</option>
                                        <option value="created">Created</option>
                                        <option value="updated">Updated</option>
                                        <option value="deleted">Deleted</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="auditable-type" placeholder="Auditable Type">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="auditable-id" placeholder="Auditable Id">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="url" placeholder="Url">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="ip-address" placeholder="Ip Address">
                                </div>
                            </div>
                            <!--div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="user-agent" placeholder="User Agent">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="from" placeholder="From" id="search-from">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="to" placeholder="To" id="search-to">
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
                <table id="table" class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable " width="100%" cellspacing="0">
                    <thead>
                        <tr role="row" class="heading">
                            <th class="">Id</th>
                            <th class="">User Type</th>
                            <th class="">User Id</th>
                            <th class="">Username</th>
                            <th class="">Email</th>
                            <th class="">Name</th>
                            <th class="">Event</th>
                            <th class="">Auditable Type</th>
                            <th class="">Auditable Id</th>
                            <th class="">Url</th>
                            <th class="">Ip Address</th>
                            <th class="">User Agent</th>
                            <th class="">Date</th>
                            <th class="">Details</th>
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
        <h4 class="modal-title"><i class="fa fa-user-plus"></i> Add User</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Name</label>
                    <div class="input-icon left">
                        <i class="fa fa-user"></i>
                        <input type="text" class="form-control" placeholder="Name" name="name"> </div>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <div class="input-icon left">
                        <i class="fa fa-envelope"></i>
                        <input type="text" class="form-control" placeholder="Email Address" name="email"> </div>
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <div class="input-icon left">
                        <i class="fa fa-graduation-cap"></i>
                        <select class="form-control" name="role-id">
                            <option value="">Select a role</option>
                        </select> </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Username</label>
                    <div class="input-icon left">
                        <i class="fa fa-user"></i>
                        <input type="text" class="form-control" placeholder="Username" name="username"> </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-icon left">
                        <i class="fa fa-lock"></i>
                        <input type="password" class="form-control" placeholder="Password" name="password"> </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="input-icon left">
                        <i class="fa fa-lock"></i>
                        <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password"> </div>
                </div>
                <div class="form-group no-margin-bottom">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                            <input type="checkbox" value="0" name="active"/>
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
<script>
    var routeRead = "{{ route('administration.trace.read') }}";
</script>
@stop

@section('custom-scripts')
<script src="{{ asset('assets/pages/scripts/administration/trace.min.js') }}" type="text/javascript"></script>
@stop