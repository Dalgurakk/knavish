@extends('layouts.master2')
@section('title','Car Models')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('page-title','Manage Models')
@section('page-sub-title','show, insert, update and delete models')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-car"></i>Models List </div>
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
                <div id="search-section" class="search-section">
                    <form>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-car"></i>
                                        <input type="text" class="form-control" name="name" placeholder="Denomination"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon left">
                                        <i class="fa fa-list-ul"></i>
                                        <select class="form-control" name="category-id">
                                            <option value="">Select a category</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                        </select> </div>
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
                            <th class="">Image</th>
                            <th class="">Denomination</th>
                            <th class="">Category</th>
                            <th class="">Category Id</th>
                            <th class="">Description</th>
                            <th class="">Enable</th>
                            <th class="">Image</th>
                            <th class="" style="min-width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-add" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false" method="post" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-car"></i> Add Model</h4>
    </div>
    <form id="form-add" enctype="multipart/form-data" method="post">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <div class="input-icon left">
                        <i class="fa fa-car"></i>
                        <input type="text" class="form-control" placeholder="Denomination" name="name"> </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <div class="input-icon left">
                        <i class="fa fa-list-ul"></i>
                        <select class="form-control" name="category-id">
                            <option value="">Select a category</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                        </select> </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Description" style="resize: none;"></textarea>
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
            <div class="col-md-6">
                <div class="form-group">
                    <label>Image</label>
                    <div class="fileinput fileinput-new" data-provides="fileinput" style="display: block;">
                        <div class="fileinput-new thumbnail car-image">
                            <img src="{{ asset('assets/pages/img/car/no-image-768x576.jpg') }}" alt=""> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail car-image" style="border: 1px solid #c2cad8;"> </div>
                        <div class="image-error-container"></div>
                        <div style="margin-top: 10px;">
                            <span class="btn default btn-file">
                                <span class="fileinput-new"><i class="fa fa-file-image-o"></i> Image</span>
                                <span class="fileinput-exists"><i class="fa fa-refresh"></i> Change</span>
                                <input type="file" name="image" data-image-error-container="image-error-container">
                            </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"><i class="fa fa-close"></i> Remove</a>
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

<div id="modal-info" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false" method="post" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-car"></i> Model Data</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <div class="input-icon left">
                        <i class="fa fa-car"></i>
                        <input type="text" class="form-control" name="name" readonly> </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <div class="input-icon left">
                        <i class="fa fa-list-ul"></i>
                        <input type="text" class="form-control" name="category" readonly> </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="4" readonly style="resize: none;"></textarea>
                </div>
                <div class="form-group no-margin-bottom">
                     <div class="mt-checkbox-list">
                         <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                             <input type="checkbox" value="1" name="active" checked onclick="return false;"/>
                             <span></span>
                         </label>
                     </div>
                 </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Image</label>
                    <div class="fileinput fileinput-new" data-provides="fileinput" style="display: block;">
                        <div class="fileinput-new thumbnail car-image">
                            <img src="{{ asset('assets/pages/img/car/no-image-768x576.jpg') }}" alt="" name="preview"> </div>
                        <div class="fileinput-preview fileinput-exists thumbnail car-image" style="border: 1px solid #c2cad8;"> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>

<div id="modal-edit" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false" method="post" enctype="multipart/form-data">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-car"></i> Edit Model</h4>
    </div>
    <form id="form-edit" enctype="multipart/form-data" method="post">
    <div class="modal-body">
        <div class="row">
            <input type="hidden" name="id">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Denomination</label>
                    <div class="input-icon left">
                        <i class="fa fa-car"></i>
                        <input type="text" class="form-control" placeholder="Denomination" name="name"> </div>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <div class="input-icon left">
                        <i class="fa fa-list-ul"></i>
                        <select class="form-control" name="category-id">
                            <option value="">Select a category</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                        </select> </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="description" rows="4" placeholder="Description" style="resize: none;"></textarea>
                </div>
                <div class="form-group no-margin-bottom">
                     <div class="mt-checkbox-list">
                         <label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Enabled
                             <input type="checkbox" value="1" name="active" checked/>
                             <span></span>
                         </label>
                     </div>
                 </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Image</label>
                    <div id="file-edit" class="fileinput fileinput-new" data-provides="fileinput" style="display: block;">
                        <div class="fileinput-new thumbnail car-image">
                            <img src="{{ asset('assets/pages/img/car/no-image-768x576.jpg') }}" alt="">
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail car-image" style="border: 1px solid #c2cad8;"> </div>
                        <div class="image-error-container"></div>
                        <div style="margin-top: 10px;">
                            <span class="btn default btn-file">
                                <span class="fileinput-new"><i class="fa fa-file-image-o"></i> Image</span>
                                <span class="fileinput-exists"><i class="fa fa-refresh"></i> Change</span>
                                <input type="file" name="image" data-image-error-container="image-error-container">
                            </span>
                            <a href="javascript:;" class="btn red fileinput-exists" data-dismiss="fileinput"><i class="fa fa-close"></i> Remove</a>
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
<script src="{{ asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
        var needUpdate = false;
        var uploadDir = '{{ $uploadDir }}';
        var noImgSrc = '{{ $noImgSrc }}';
        var imageOperation = 0;

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
                "url": "{{ route('car.model.read') }}",
                "type": "POST",
                "complete": function(xhr, textStatus) {
                    if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                }
            },
            "order": [[ 2, "asc" ]],
            columns: [
                {data: 'id', name: 'id', visible: false},
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    "data": function ( row, type, val, meta ) {
                        var data =
                            '<div class="car-image-container">' +
                                '<img class="car-image-thumbnail" src="' + noImgSrc + '" />' +
                            '</div>';
                        if (row.image != null) {
                            data =
                            '<div class="car-image-container">' +
                                '<img class="car-image-thumbnail" src="' + uploadDir + '/' + row.image + '" />' +
                            '</div>';
                        }
                        return data;
                    }
                },
                {data: 'name', name: 'name'},
                {data: 'category', name: 'category'},
                {data: 'category_id', name: 'category_id', visible: false},
                {data: 'description', name: 'description'},
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
                    data: 'image',
                    name: 'image',
                    visible: false
                },
                {
                    targets: 'actions',
                    orderable: false,
                    name: 'actions',
                    "className": "dt-center",
                    "data": function ( row, type, val, meta ) {
                        var data =
                        '<div class="dt-actions">' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info">' +
                                '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-edit" data-toggle="modal" href="#modal-edit">' +
                                '<i class="icon-pencil btn-action-icon"></i></a>' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-delete" href="javascript:;" data-popout="true" data-placement="left"' +
                                'data-btn-ok-label="Yes" data-btn-ok-class="btn-sm btn-success btn-confirmation"  data-btn-ok-icon-content="check" ' +
                                'data-btn-cancel-label="No" data-btn-cancel-class="btn-sm btn-danger btn-confirmation" data-btn-cancel-icon-content="close" data-title="Are you sure?" data-content="">' +
                                '<i class="icon-trash btn-action-icon"></i></a>' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-duplicate" href="javascript:;" data-popout="true" data-placement="left"' +
                                'data-btn-ok-label="Yes" data-btn-ok-class="btn-sm btn-success btn-confirmation"  data-btn-ok-icon-content="check" ' +
                                'data-btn-cancel-label="No" data-btn-cancel-class="btn-sm btn-danger btn-confirmation" data-btn-cancel-icon-content="close" data-title="Are you sure?" data-content="">' +
                                '<i class="icon-docs btn-action-icon"></i></a>' +
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
            $('#modal-add :input[name=image]').attr('type', 'file');
        });

        $('.lenght-option').on('click', function () {
            var value = $(this).attr('data');
            $(this).parent().parent().prev('a').text(value);
            var select = $('select[name=table_length]');
            select.val(value);
            select.change();
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
                "category-id": {
                    required: true
                },
                "image": {
                    accept: "image/*",
                    extension: "png|jpg|jpeg"
                }
            },
            messages: {
                image: {
                    accept: "Please enter a valid image.",
                    extension: "Extensions allowed: png, jpg, jpeg."
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
                }else if (element.attr("data-image-error-container")) {
                    var container = element.attr("data-image-error-container");
                    error.appendTo($('#modal-add').find('.' + container));
                }
                else {
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
                $.ajax({
                    "url": "{{ route('car.model.create') }}",
                    "type": "POST",
                    "data": new FormData(formAdd[0]),
                    "contentType": false,
                    "processData": false,
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
                                if (option == 'apply') {
                                    formAdd.validate().resetForm();
                                    formAdd[0].reset();
                                    $('#modal-add :input[name=image]').attr('type', 'file');
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
                },
                "category-id": {
                    required: true
                },
                "image": {
                    accept: "image/*",
                    extension: "png|jpg|jpeg"
                }
            },
            messages: {
                image: {
                    accept: "Please enter a valid image.",
                    extension: "Extensions allowed: png, jpg, jpeg."
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
                } else if (element.attr("data-image-error-container")) {
                    var container = element.attr("data-image-error-container");
                    error.appendTo($('#modal-edit').find('.' + container));
                }
                 else {
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
                formData.append('imageOperation', imageOperation);
                $.ajax({
                    "url": "{{ route('car.model.update') }}",
                    "type": "POST",
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

                                if (option == 'apply') {
                                    formEdit.validate().resetForm();
                                    formEdit[0].reset();
                                    $('#modal-edit :input[name=image]').attr('type', 'file');
                                    imageOperation = 0;

                                    var data = response.data;
                                    $('#modal-edit :input[name=id]').val(data.id);
                                    $('#modal-edit :input[name=name]').val(data.name);
                                    $('#modal-edit :input[name=description]').val(data.description);
                                    $('#modal-edit :input[name=category-id]').val(data.category_id);

                                    var src = data.image != null ? uploadDir + '/' + data.image : noImgSrc;
                                    $('#modal-edit').find('img').attr('src', src);

                                    if (data.active == 1) {
                                        $('#modal-edit :input[name=active]').prop('checked', 'checked');
                                        $('#modal-edit :input[name=active]').val(1);
                                    }
                                    else {
                                        $('#modal-edit :input[name=active]').prop('checked', '');
                                        $('#modal-edit :input[name=active]').val(0);
                                    }
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

        $('.cancel-form').on('click', function(e) {
            if(needUpdate) {
                table.draw();
                needUpdate = false;
            }
        });

        $('#file-edit').on('clear.bs.fileinput', function() {
            $('#modal-edit').find('img').attr('src', noImgSrc);
            imageOperation = 2;
        });

        $('#file-edit').on('change.bs.fileinput', function() {
            imageOperation = 1;
        });

        $('#table tbody').on( 'click', '.dt-edit', function (e) {
            formEdit.validate().resetForm();
            formEdit[0].reset();
            $('#modal-edit :input[name=image]').attr('type', 'file');
            imageOperation = 0;

            var data = table.row( $(this).parents('tr') ).data();
            $('#modal-edit :input[name=id]').val(data['id']);
            $('#modal-edit :input[name=name]').val(data['name']);
            $('#modal-edit :input[name=description]').val(data['description']);
            $('#modal-edit :input[name=category-id]').val(data['category_id']);

            var src = data['image'] != null ? uploadDir + '/' + data['image'] : noImgSrc;
            $('#modal-edit').find('img').attr('src', src);

            if (data['active'] == 1) {
                $('#modal-edit :input[name=active]').prop('checked', 'checked');
                $('#modal-edit :input[name=active]').val(1);
            }
            else {
                $('#modal-edit :input[name=active]').prop('checked', '');
                $('#modal-edit :input[name=active]').val(0);
            }
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-view', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            $('#modal-info :input[name=name]').val(data['name']);
            $('#modal-info :input[name=description]').val(data['description']);
            $('#modal-info :input[name=category]').val(data['category']);
            var src = data['image'] != null ? uploadDir + '/' + data['image'] : noImgSrc;
            $('#modal-info').find('img').attr('src', src);
            if (data['active'] == 1) {
                $('#modal-info :input[name=active]').prop('checked', 'checked');
                $('#modal-info :input[name=active]').val(1);
            }
            else {
                $('#modal-info :input[name=active]').prop('checked', '');
                $('#modal-info :input[name=active]').val(0);
            }
            e.preventDefault();
        });

        $('#table tbody').on( 'click', '.dt-duplicate', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                $.ajax({
                    url: "{{ route('car.model.duplicate') }}",
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

        $('#table tbody').on( 'click', '.dt-delete', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                $.ajax({
                    url: "{{ route('car.model.delete') }}",
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

        $('.btn-search-reset').on('click', function (e) {
            e.preventDefault();
            $('#search-section :input[name=name]').val('');
            $('#search-section :input[name=category-id]').val('');
            $('#search-section :input[name=active]').val('');
        });

        $('.btn-search-cancel').on('click', function (e) {
            e.preventDefault();
            $('#search-section').slideToggle();
        });

        $('.btn-search-submit').on( 'click', function (e) {
            e.preventDefault();
            var category = $('#search-section :input[name=category-id]').val() != "" ? $('#search-section :input[name=category-id] option:selected').text() : '';
            table
                .columns('name:name').search($('#search-section :input[name=name]').val())
                .columns('category:name').search(category)
                .columns('active:name').search($('#search-section :input[name=active]').val())
            .draw();
        });
    });
</script>
@stop