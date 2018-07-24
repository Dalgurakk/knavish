@extends('layouts.master2')
@section('title','Hotels')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jstree2/dist/themes/default/style.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/fancybox/source/jquery.fancybox.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-file-upload/blueimp-gallery/blueimp-gallery.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-file-upload/css/jquery.fileupload.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-file-upload/css/jquery.fileupload-ui.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Manage Hotels')
@section('page-sub-title','show, insert, update and delete hotels')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-building-o"></i>Hotels List </div>
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
                                        <i class="fa fa-building-o"></i>
                                        <input type="text" class="form-control" name="name" placeholder="Denomination"> </div>
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
                            <th class="">Category</th>
                            <th class="">Hotel Chain</th>
                            <th class="">Enable</th>
                            <th class="">Hotel</th>
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
        <h4 class="modal-title"><i class="fa fa-building-o"></i> Add Hotel</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="form-control trigger-location" placeholder="Country" name="country-text">
                    <input type="hidden" name="country-id">
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" class="form-control trigger-location" placeholder="State" name="state-text">
                    <input type="hidden" name="state-id">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" class="form-control trigger-location" placeholder="City" name="city-text">
                    <input type="hidden" name="city-id">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" placeholder="Address" name="address">
                    <!--textarea class="form-control" name="address" rows="4" placeholder="Address" style="resize: none;height: 72px;"></textarea-->
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" class="form-control" placeholder="Postal Code" name="postal-code">
                </div>
                <div class="form-group">
                    <label>Turistic License</label>
                    <input type="text" class="form-control" placeholder="Turistic License" name="turistic-licence">
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
                    <select class="form-control hotel-chain" name="hotel-chain-id">
                        <option value="">Hotel Chain</option>
                    @foreach ($hotelsChain as $h)
                        <option value="{{ $h->id }}">{{ $h->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                    <label>Admin. Phone</label>
                    <input type="text" class="form-control" placeholder="Admin. Phone" name="admin-phone">
                </div>
                <div class="form-group">
                    <label>Admin. Fax</label>
                    <input type="text" class="form-control" placeholder="Admin. Fax" name="admin-fax">
                </div>
                <div class="form-group">
                    <label>Web Site</label>
                    <input type="text" class="form-control" placeholder="Web Site" name="web-site">
                </div>
                <div class="form-group">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px;"> Enabled
                            <input type="checkbox" value="1" name="active"/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <!--div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Commercial Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Commercial Description" style="resize: none;"></textarea>
                </div>
            </div-->
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
        <h4 class="modal-title"><i class="fa fa-building-o"></i> Hotel Data</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" name="name" readonly>
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
                <div class="form-group">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px;"> Enabled
                            <input type="checkbox" value="1" name="active" onclick="return false;"/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <!--div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Commercial Description</label>
                    <textarea class="form-control" name="description" rows="4" readonly style="resize: none;"></textarea>
                </div>
            </div-->
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>

<div id="modal-edit" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-building-o"></i> Edit Hotel</h4>
    </div>
    <form id="form-edit">
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id" value="0">
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <input type="text" class="form-control" placeholder="Denomination" name="name">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" class="form-control trigger-location" placeholder="Country" name="country-text">
                    <input type="hidden" name="country-id">
                </div>
                <div class="form-group">
                    <label>State</label>
                    <input type="text" class="form-control trigger-location" placeholder="State" name="state-text">
                    <input type="hidden" name="state-id">
                </div>
                <div class="form-group">
                    <label>City</label>
                    <input type="text" class="form-control trigger-location" placeholder="City" name="city-text">
                    <input type="hidden" name="city-id">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" class="form-control" placeholder="Address" name="address">
                </div>
                <div class="form-group">
                    <label>Postal Code</label>
                    <input type="text" class="form-control" placeholder="Postal Code" name="postal-code">
                </div>
                <div class="form-group">
                    <label>Turistic License</label>
                    <input type="text" class="form-control" placeholder="Turistic License" name="turistic-licence">
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
                    <select class="form-control hotel-chain" name="hotel-chain-id">
                        <option value="">Hotel Chain</option>
                    @foreach ($hotelsChain as $h)
                        <option value="{{ $h->id }}">{{ $h->name }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" placeholder="Email" name="email">
                </div>
                <div class="form-group">
                    <label>Admin. Phone</label>
                    <input type="text" class="form-control" placeholder="Admin. Phone" name="admin-phone">
                </div>
                <div class="form-group">
                    <label>Admin. Fax</label>
                    <input type="text" class="form-control" placeholder="Admin. Fax" name="admin-fax">
                </div>
                <div class="form-group">
                    <label>Web Site</label>
                    <input type="text" class="form-control" placeholder="Web Site" name="web-site">
                </div>
                <div class="form-group">
                    <div class="mt-checkbox-list">
                        <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px;"> Enabled
                            <input type="checkbox" value="1" name="active"/>
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <!--div class="col-md-12 col-sm-12">
                <div class="form-group">
                    <label>Commercial Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Commercial Description" style="resize: none;"></textarea>
                </div>
            </div-->
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green" data="apply"><i class="fa fa-repeat"></i> Apply</button>
        <button type="submit" class="btn green" data="accept"><i class="fa fa-check"></i> Accept</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
    </form>
</div>

<div id="modal-location" class="modal fade custom-container" tabindex="-1" data-width="550" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-globe"></i> Select Location</h4>
    </div>
    <div class="modal-body">
        <div id="tree"> </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>

<div id="modal-image" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="icon-camera"></i> Hotel Images</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <form id="fileupload" action="{{ route('hotel.upload.image') }}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id">
                    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                    <div class="row fileupload-buttonbar">
                        <div class="col-lg-6">
                            <!-- The fileinput-button span is used to style the file input field as button -->
                            <span class="btn green fileinput-button">
                                <i class="fa fa-plus"></i>
                                <span> Add files... </span>
                                <input type="file" name="files[]" multiple=""> </span>
                            <button type="submit" class="btn blue start">
                                <i class="fa fa-upload"></i>
                                <span> Start upload </span>
                            </button>
                            <!--button type="reset" class="btn warning cancel">
                                <i class="fa fa-ban-circle"></i>
                                <span> Cancel upload </span>
                            </button-->
                            <button type="button" class="btn red delete">
                                <i class="fa fa-trash"></i>
                                <span> Delete </span>
                            </button>
                            <input type="checkbox" class="toggle">
                            <!-- The global file processing state -->
                            <span class="fileupload-process"> </span>
                        </div>
                        <!-- The global progress information -->
                        <div class="col-lg-6 fileupload-progress fade" style="display: block">
                            <!-- The global progress bar -->
                            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="margin-bottom: 10px;">
                                <div class="progress-bar progress-bar-success" style="width:0%;"> </div>
                            </div>
                            <!-- The extended global progress information -->
                            <div class="progress-extended" style="font-size:12px;"> &nbsp; </div>
                        </div>
                    </div>
                    <!-- The table listing the files available for upload/download -->
                    <table role="presentation" class="table table-striped table-hover clearfix" style="margin-bottom:0; margin-top: 5px;">
                        <tbody class="files"> </tbody>
                    </table>
                </form>
            </div>
        </div>
        <!-- The blueimp Gallery widget -->
        <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
            <div class="slides"> </div>
            <h3 class="title"></h3>
            <a class="prev"> ‹ </a>
            <a class="next"> › </a>
            <a class="close white"> </a>
            <a class="play-pause"> </a>
            <ol class="indicator"> </ol>
        </div>
        <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
        <script id="template-upload" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-upload fade">
                <td style="width: 20%;">
                    <span class="preview"></span>
                </td>
                <td style="width: 40%; word-wrap: break-word; min-width: 100px; max-width: 100px;">
                    <p class="name">{%=file.name%}</p>
                    <strong class="error label label-danger"></strong>
                </td>
                <td style="width: 15%;">
                    <p class="size" style="margin-bottom: 0px; margin-top: 12px;">Processing...</p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" style="margin:0;height: 14px;">
                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                    </div>
                </td>
                <td style="width: 25%;"> {% if (!i && !o.options.autoUpload) { %}
                    <p style="margin:15px 0;">
                    <button class="btn blue btn-sm start" disabled>
                        <i class="fa fa-upload"></i>
                        <span>Start</span>
                    </button> {% } %} {% if (!i) { %}
                    <button class="btn red btn-sm cancel">
                        <i class="fa fa-ban"></i>
                        <span>Cancel</span>
                    </button></p> {% } %} </td>
            </tr> {% } %}
        </script>
        <!-- The template to display files available for download -->
        <script id="template-download" type="text/x-tmpl"> {% for (var i=0, file; file=o.files[i]; i++) { %}
            <tr class="template-download fade">
                <td style="width: 20%;">
                    <span class="preview"> {% if (file.thumbnailUrl) { %}
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery>
                            <img src="{%=file.thumbnailUrl%}">
                        </a> {% } %} </span>
                </td>
                <td style="width: 40%; word-wrap: break-word; min-width: 100px; max-width: 100px;">
                    <p class="name"> {% if (file.url) { %}
                        <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl? 'data-gallery': ''%}>{%=file.name%}</a> {% } else { %}
                        <span>{%=file.name%}</span> {% } %} </p> {% if (file.error) { %}
                    <div>
                        <span class="label label-danger">Error</span> {%=file.error%}</div> {% } %} </td>
                <td style="width: 15%;">
                    <p> <span class="size">{%=o.formatFileSize(file.size)%}</span></p>
                </td>
                <td style="width: 25%;"> {% if (file.deleteUrl) { %}
                    <p style="margin:15px 0;">
                    <button class="btn red delete btn-sm" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}" {% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}' {% } %}>
                        <i class="fa fa-trash-o"></i>
                        <span>Delete</span>
                    </button>
                    <input type="checkbox" name="delete" value="1" class="toggle"> {% } else { %}
                    <button class="btn yellow cancel btn-sm">
                        <i class="fa fa-ban"></i>
                        <span>Cancel</span>
                    </button></p> {% } %} </td>
            </tr> {% } %}
        </script>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
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
<script src="{{ asset('assets/global/plugins/jstree2/dist/jstree.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/fancybox/source/jquery.fancybox.pack.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/vendor/tmpl.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/vendor/load-image.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/vendor/canvas-to-blob.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/blueimp-gallery/jquery.blueimp-gallery.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/jquery.fileupload.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/jquery.fileupload-process.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/jquery.fileupload-image.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/jquery.fileupload-validate.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-file-upload/js/jquery.fileupload-ui.js') }}" type="text/javascript"></script>
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
        var needUpdate = false;
        var inputSelected;
        var inputSelectedHidden;

        /*$(".hotel-chain").select2({
            width: "off"
        });*/

         // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload({
            disableImageResize: false,
            autoUpload: false,
            disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
            maxFileSize: 5000000,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
        });

        // Enable iframe cross-domain access via redirect option:
        $('#fileupload').fileupload(
            'option',
            {
                previewMaxWidth: 100,
                previewMaxHeight: 78
            },
            'redirect',
            window.location.href.replace(
                /\/[^\/]*$/,
                '/cors/result.html?%s'
            )
        );

        // Upload server status check for browsers with CORS support:
        if ($.support.cors) {
            $.ajax({
                type: 'HEAD'
            }).fail(function () {
                $('<div class="alert alert-danger"/>')
                    .text('Upload server currently unavailable - ' +
                            new Date())
                    .appendTo('#fileupload');
            });
        }

        $('.trigger-location').on('click, focus', function(e) {
            inputSelected = $(this);
            inputSelectedHidden = $(this).next();
            $('#modal-location').modal('show');
        });

        $('.hotel-category').barrating({
            theme: 'fontawesome-stars'
        });

        $('#tree')
            .on("changed.jstree", function (e, data) {
                if(data.selected.length) {
                    var obj = data.instance.get_node(data.selected[0]).data;
                    $(inputSelected).val(obj.name);
                    $(inputSelectedHidden).val(obj.id);
                    $('#node-data :input[name=name]').val(obj.name);
                    $('#modal-location').modal('hide');
                }
                $('#tree').jstree("deselect_all", true);
            })
            .jstree({
                'core' : {
                    'data' : {
                        "type": "POST",
                        "dataType": "json",
                        "url": "{{ route('location.read.active') }}",
                        "data" : function (node) {
                            return { 'id' : node.id };
                        }
                    }
                },
                "plugins" : [
                    "state",
                    "contextmenu"
                ]
            })
        ;

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
                "url": "{{ route('hotel.read') }}",
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
                {
                    data: 'category',
                    name: 'category',
                    "data": function ( row, type, val, meta ) {
                        var stars = parseInt(row.category);
                        var data =
                        '<div class="br-wrapper br-theme-fontawesome-stars">'+
                            '<div class="br-widget br-readonly">';
                        for (var i = 0; i < stars; i++) {
                            data +=
                                '<a href="#" class="br-selected"></a>';
                        }
                        data +=
                            '</div>'+
                        '</div>';
                        return data;
                    }
                },
                {data: 'chain', name: 'chain'},
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
                {data: 'hotel', name: 'hotel', visible: false},
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
                            '<i class="icon-trash btn-action-icon"></i></a>' +
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-image" href="javascript:;">' +
                            '<i class="icon-camera btn-action-icon"></i></a>' +
                        '</div>';
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
            $('#modal-add .js-data-ajax').val(null).trigger('change');
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

        var formAdd = $('#form-add');
        formAdd.validate({
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: false,
            ignore: "",
            rules: {
                name: {
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
                }else if (element.hasClass("hotel-category")) {
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
                var formData = new FormData(formAdd[0]);
                $.ajax({
                    "url": "{{ route('hotel.create') }}",
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
                $.ajax({
                    "url": "{{ route('hotel.update') }}",
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
                        }
                    }
                });
            }
        });

        $('#table tbody').on( 'click', '.dt-view', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            var hotel = data['hotel'];

            $('#modal-info :input[name=name]').val(hotel.name);
            $('#modal-info :input[name=country-text]').val(hotel.country);
            $('#modal-info :input[name=state-text]').val(hotel.state);
            $('#modal-info :input[name=city-text]').val(hotel.city);
            $('#modal-info :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-info :input[name=address]').val(hotel.address);
            $('#modal-info :input[name=category]').barrating('readonly', true);
            var category = hotel.category != null ? hotel.category : '';
            $('#modal-info :input[name=category]').barrating('set', category);
            $('#modal-info :input[name=hotel-chain]').val(hotel.chain);
            $('#modal-info :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-info :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-info :input[name=web-site]').val(hotel.web_site);
            $('#modal-info :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-info :input[name=email]').val(hotel.email);
            $('#modal-info :input[name=description]').val(hotel.description);
            if (hotel.active == 1) {
                $('#modal-info :input[name=active]').prop('checked', 'checked');
                $('#modal-info :input[name=active]').val(1);
            }
            else {
                $('#modal-info :input[name=active]').prop('checked', '');
                $('#modal-info :input[name=active]').val(0);
            }
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-edit', function (e) {
            $('.parent-select').html('');
            formEdit.validate().resetForm();
            formEdit[0].reset();
            var data = table.row( $(this).parents('tr') ).data();
            var hotel = data['hotel'];

            $('#modal-edit :input[name=id]').val(data['id']);
            $('#modal-edit :input[name=name]').val(hotel.name);
            $('#modal-edit :input[name=country-text]').val(hotel.country);
            $('#modal-edit :input[name=state-text]').val(hotel.state);
            $('#modal-edit :input[name=city-text]').val(hotel.city);
            $('#modal-edit :input[name=country-id]').val(hotel.country_id);
            $('#modal-edit :input[name=state-id]').val(hotel.state_id);
            $('#modal-edit :input[name=city-id]').val(hotel.city_id);
            $('#modal-edit :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-edit :input[name=address]').val(hotel.address);
            var category = hotel.category != null ? hotel.category : '';
            $('#modal-edit :input[name=category]').barrating('set', category);
            $('#modal-edit :input[name=hotel-chain-id]').val(hotel.chain_id);
            $('#modal-edit :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-edit :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-edit :input[name=web-site]').val(hotel.web_site);
            $('#modal-edit :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-edit :input[name=email]').val(hotel.email);
            $('#modal-edit :input[name=description]').val(hotel.description);
            if (hotel.active == 1) {
                $('#modal-edit :input[name=active]').prop('checked', 'checked');
                $('#modal-edit :input[name=active]').val(1);
            }
            else {
                $('#modal-edit :input[name=active]').prop('checked', '');
                $('#modal-edit :input[name=active]').val(0);
            }
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-delete', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                $.ajax({
                    url: "{{ route('hotel.delete') }}",
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

        $('#table tbody').on( 'click', '.dt-image', function (e) {
            var $form = $('#fileupload');
            $form.find(".files").empty();
            var data = table.row( $(this).parents('tr') ).data();
            var id = data['id'];
            $('#modal-image :input[name=id]').val(id);
            /*$form.fileupload('option', 'done').call($form, $.Event('done'), {result: {files: files}});*/
            $('#fileupload').addClass('fileupload-processing');
            $.ajax({
                url: "{{ route('hotel.images') }}",
                dataType: 'json',
                data: { id: id },
                type: "POST",
                context: $('#fileupload')[0]
            }).always(function () {
                $(this).removeClass('fileupload-processing');
            }).done(function (result) {
                $(this).fileupload('option', 'done')
                    .call(this, $.Event('done'), {result: result});
                $('#modal-image').modal('layout');
            });
            $('#modal-image').modal('show');
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
            $('#search-section :input[name=active]').val('');
        });

        $('.btn-search-cancel').on('click', function (e) {
            e.preventDefault();
            $('#search-section').slideToggle();
        });

        $('.btn-search-submit').on( 'click', function (e) {
            e.preventDefault();
            table
                .columns('name:name').search($('#search-section :input[name=name]').val())
                .columns('active:name').search($('#search-section :input[name=active]').val())
            .draw();
        });
    });
</script>
@stop