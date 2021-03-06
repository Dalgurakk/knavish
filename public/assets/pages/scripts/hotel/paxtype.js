$(document).ready(function () {
    var needUpdate = false;
    var object = null;

    $.fn.dataTable.ext.errMode = 'none';
    var table = $('#table').on('error.dt', function(e, settings, techNote, message) {

    }).on( 'processing.dt', function ( e, settings, processing ) {
        App.showMask(processing, $(this));
        App.reloadToolTips();
    }).on('init.dt', function() {

    }).DataTable({
        "processing": true,
        "serverSide": true,
        //"sDom": "lftip",
        "sDom": "ltip",
        "iDisplayLength" : 25,
        "ajax": {
            "url": routeRead,
            "type": "POST",
            "complete": function(xhr, textStatus) {
                if (xhr.status == '419') {
                    location.reload(true);
                }
                else if (xhr.status != '200') {
                    toastr['error']("Please check your connection and try again.", "Error on loading the content");
                }
            }
        },
        "order": [[ 2, "asc" ]],
        columns: [
            {data: 'id', name: 'id', visible: false},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'type', name: 'type'},
            {data: 'agefrom', name: 'agefrom'},
            {data: 'ageto', name: 'ageto'},
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
                    var data = '<div class="dt-actions">' +
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info">' +
                        '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>'+
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-edit" data-toggle="modal" href="#modal-edit">' +
                        '<i class="icon-pencil btn-action-icon"></i></a>' +
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-delete" href="javascript:;" data-popout="true" data-placement="left"' +
                        'data-btn-ok-label="Yes" data-btn-ok-class="btn-sm btn-success"  data-btn-ok-icon-content="check" ' +
                        'data-btn-cancel-label="No" data-btn-cancel-class="btn-sm btn-danger" data-btn-cancel-icon-content="close" data-title="Are you sure?" data-content="">' +
                        '<i class="icon-trash btn-action-icon"></i></a>' +
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
            code: {
                required: true
            },
            agefrom: {
                required: true,
                number: true,
                min:0,
                max: 99
            },
            ageto: {
                required: true,
                number: true,
                min:0,
                max: 99,
                greaterThan: '#modal-add :input[name=agefrom]'
            },
            type: {
                required: true,
                number: true,
                min: 1,
                max: 3
            }
        },
        messages: {
            ageto: {
                greaterThan: 'Must be greater than Age From.'
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
            $.ajax({
                "url": routeCreate,
                "type": "POST",
                "data": formAdd.serialize(),
                "beforeSend": function() {
                    App.showMask(true, formAdd);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formAdd);
                    if (xhr.status == '419') {
                        location.reload(true);
                    }
                    else if (xhr.status != '200') {
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
            },
            code: {
                required: true
            },
            agefrom: {
                required: true,
                number: true,
                min:0,
                max: 99
            },
            ageto: {
                required: true,
                number: true,
                min:0,
                max: 99,
                greaterThan: '#modal-edit :input[name=agefrom]'
            },
            type: {
                required: true,
                number: true,
                min: 1,
                max: 3
            }
        },
        messages: {
            ageto: {
                greaterThan: 'Must be greater than From age.'
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
            var formData = new FormData(formEdit[0]);
            $.ajax({
                "url": routeUpdate,
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
                    if (xhr.status == '419') {
                        location.reload(true);
                    }
                    else if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            toastr['success'](response.message, "Success");
                            needUpdate = true;
                            $(form).find("button.cancel-form").click();
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
        $('#modal-info :input[name=code]').val(data['code']);
        $('#modal-info :input[name=name]').val(data['name']);
        $('#modal-info :input[name=type]').val(data['type']);
        $('#modal-info :input[name=agefrom]').val(data['agefrom']);
        $('#modal-info :input[name=ageto]').val(data['ageto']);
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

    $('#table tbody').on( 'click', '.dt-edit', function (e) {
        formEdit.validate().resetForm();
        formEdit[0].reset();
        var data = table.row( $(this).parents('tr') ).data();
        object = data['object'];
        $('#modal-edit :input[name=id]').val(data['id']);
        $('#modal-edit :input[name=code]').val(data['code']);
        $('#modal-edit :input[name=name]').val(data['name']);
        $('#modal-edit :input[name=type]').val(object.type).change();
        $('#modal-edit :input[name=agefrom]').val(data['agefrom']);
        $('#modal-edit :input[name=ageto]').val(data['ageto']);

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

    var requestDelete = false;
    $('#table tbody').on( 'click', '.dt-delete', function (e) {
        if (!requestDelete) {
            var data = table.row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                requestDelete = true;
                $.ajax({
                    url: routeDelete,
                    "type": "POST",
                    "data":  {
                        id: data['id']
                    },
                    "beforeSend": function() {
                        App.showMask(true, formAdd);
                    },
                    "complete": function(xhr, textStatus) {
                        requestDelete = false;
                        App.showMask(false, formAdd);
                        if (xhr.status == '419') {
                            location.reload(true);
                        }
                        else if (xhr.status != '200') {
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
        }
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
        $('#search-section :input[name=code]').val('');
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
            .columns('code:name').search($('#search-section :input[name=code]').val())
            .columns('name:name').search($('#search-section :input[name=name]').val())
            .columns('active:name').search($('#search-section :input[name=active]').val())
            .draw();
    });

    $('.excel').on('click',function(){
        var query = {
            code: $('#search-section :input[name=code]').val(),
            name: $('#search-section :input[name=name]').val(),
            active: $('#search-section :input[name=active]').val()
        };
        var url = routeExcel + "?" + $.param(query);
        window.location = url;
    });
});