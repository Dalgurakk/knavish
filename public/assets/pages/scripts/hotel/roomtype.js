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
                if (xhr.status != '200') {
                    toastr['error']("Please check your connection and try again.", "Error on loading the content");
                }
            }
        },
        "order": [[ 1, "asc" ]],
        columns: [
            {data: 'id', name: 'id', visible: false},
            {data: 'code', name: 'code'},
            {data: 'name', name: 'name', width: '35%'},
            {data: 'max_pax', name: 'maxpax'},
            {data: 'max_adult', name: 'maxadult'},
            {data: 'min_adult', name: 'minadult'},
            {data: 'max_children', name: 'maxchildren'},
            {data: 'min_children', name: 'minchildren'},
            {data: 'max_infant', name: 'maxinfant'},
            {data: 'min_infant', name: 'mininfant'},
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
    });

    $('.lenght-option').on('click', function () {
        var value = $(this).attr('data');
        $(this).parent().parent().prev('a').text(value);
        var select = $('select[name=table_length]');
        select.val(value);
        select.change();
    });

    $.validator.addMethod('notGreaterThan', function (value, element, param) {
        return this.optional(element) || parseInt(value) <= parseInt($(param).val());
    }, 'Invalid value.');

    $.validator.addMethod('addTotalPaxValue', function (value, element) {
        var ad = $.isNumeric($('#modal-add :input[name=minadult]').val()) ? parseInt($('#modal-add :input[name=minadult]').val()) : 0;
        var ch = $.isNumeric($('#modal-add :input[name=minchildren]').val()) ? parseInt($('#modal-add :input[name=minchildren]').val()) : 0;
        var inf = $.isNumeric($('#modal-add :input[name=mininfant]').val()) ? parseInt($('#modal-add :input[name=mininfant]').val()) : 0;
        var total = ad + ch + inf;
        return this.optional(element) || parseInt(value) >= total;
    }, 'The total of the min pax value can not exceed the max pax value.');

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
            maxpax: {
                required: true,
                digits: true,
                min:1,
                addTotalPaxValue: true
            },
            maxadult: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-add :input[name=maxpax]'
            },
            minadult: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-add :input[name=maxadult]'
            },
            maxchildren: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-add :input[name=maxpax]'
            },
            minchildren: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-add :input[name=maxchildren]'
            },
            maxinfant: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-add :input[name=maxpax]'
            },
            mininfant: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-add :input[name=maxinfant]'
            }
        },
        messages: {
            maxadult: {
                notGreaterThan: 'Must not be greater than Max Pax.'
            },
            maxchildren: {
                notGreaterThan: 'Must not be greater than Max Pax.'
            },
            maxinfant: {
                notGreaterThan: 'Must not be greater than Max Pax.'
            },
            minadult: {
                notGreaterThan: 'Must not be greater than Max Adult.'
            },
            minchildren: {
                notGreaterThan: 'Must not be greater than Max Children.'
            },
            mininfant: {
                notGreaterThan: 'Must not be greater than Max Infant.'
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

    $.validator.addMethod('editTotalPaxValue', function (value, element) {
        var ad = $.isNumeric($('#modal-edit :input[name=minadult]').val()) ? parseInt($('#modal-edit :input[name=minadult]').val()) : 0;
        var ch = $.isNumeric($('#modal-edit :input[name=minchildren]').val()) ? parseInt($('#modal-edit :input[name=minchildren]').val()) : 0;
        var inf = $.isNumeric($('#modal-edit :input[name=mininfant]').val()) ? parseInt($('#modal-edit :input[name=mininfant]').val()) : 0;
        var total = ad + ch + inf;
        return this.optional(element) || parseInt(value) >= total;
    }, 'The total of the min pax value can not exceed the max pax value.');

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
            maxpax: {
                required: true,
                digits: true,
                min:1,
                editTotalPaxValue: true
            },
            maxadult: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-edit :input[name=maxpax]'
            },
            minadult: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-edit :input[name=maxadult]'
            },
            maxchildren: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-edit :input[name=maxpax]'
            },
            minchildren: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-edit :input[name=maxchildren]'
            },
            maxinfant: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-edit :input[name=maxpax]'
            },
            mininfant: {
                required: true,
                digits: true,
                min:0,
                notGreaterThan: '#modal-edit :input[name=maxinfant]'
            }
        },
        messages: {
            maxadult: {
                notGreaterThan: 'Must not be greater than Max Pax.'
            },
            maxchildren: {
                notGreaterThan: 'Must not be greater than Max Pax.'
            },
            maxinfant: {
                notGreaterThan: 'Must not be greater than Max Pax.'
            },
            minadult: {
                notGreaterThan: 'Must not be greater than Max Adult.'
            },
            minchildren: {
                notGreaterThan: 'Must not be greater than Max Children.'
            },
            mininfant: {
                notGreaterThan: 'Must not be greater than Max Infant.'
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
            var needEdit = false;
            var formData = new FormData(formEdit[0]);
            var active = formData.get('active') == '1' ? 1 : 0;
            console.log(object);
            if (
                object.code != formData.get('code') ||
                object.name != formData.get('name') ||
                object.max_pax != formData.get('maxpax') ||
                object.max_adult != formData.get('maxadult') ||
                object.min_adult != formData.get('minadult') ||
                object.max_children != formData.get('maxchildren') ||
                object.min_children != formData.get('minchildren') ||
                object.max_infant != formData.get('maxinfant') ||
                object.min_infant != formData.get('mininfant') ||
                object.active != active
            ) {
                needEdit = true;
            }
            if(needEdit) {
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
                        if (xhr.status != '200') {
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
            else {
                $(form).find("button.cancel-form").click();
            }
        }
    });

    $('#table tbody').on( 'click', '.dt-view', function (e) {
        var data = table.row( $(this).parents('tr') ).data();
        $('#modal-info :input[name=code]').val(data['code']);
        $('#modal-info :input[name=name]').val(data['name']);
        $('#modal-info :input[name=maxpax]').val(data['max_pax']);
        $('#modal-info :input[name=maxpax]').val(data['max_pax']);
        $('#modal-info :input[name=maxadult]').val(data['max_adult']);
        $('#modal-info :input[name=minadult]').val(data['min_adult']);
        $('#modal-info :input[name=maxchildren]').val(data['max_children']);
        $('#modal-info :input[name=minchildren]').val(data['min_children']);
        $('#modal-info :input[name=maxinfant]').val(data['max_infant']);
        $('#modal-info :input[name=mininfant]').val(data['min_infant']);
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
        $('#modal-edit :input[name=maxpax]').val(data['max_pax']);
        $('#modal-edit :input[name=maxpax]').val(data['max_pax']);
        $('#modal-edit :input[name=maxadult]').val(data['max_adult']);
        $('#modal-edit :input[name=minadult]').val(data['min_adult']);
        $('#modal-edit :input[name=maxchildren]').val(data['max_children']);
        $('#modal-edit :input[name=minchildren]').val(data['min_children']);
        $('#modal-edit :input[name=maxinfant]').val(data['max_infant']);
        $('#modal-edit :input[name=mininfant]').val(data['min_infant']);
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

    $('#table tbody').on( 'click', '.dt-delete', function (e) {
        var data = table.row( $(this).parents('tr') ).data();
        $(this).confirmation('show');
        var sendRequest = false;
        $(this).on('confirmed.bs.confirmation', function () {
            if(!sendRequest){
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
                    },
                    success: function () {
                        sendRequest = true;
                    }
                });
            }
        });
        e.preventDefault();
    });

    $('#table tbody').on( 'click', '.dt-duplicate', function (e) {
        var data = table.row( $(this).parents('tr') ).data();
        $(this).confirmation('show');
        $(this).on('confirmed.bs.confirmation', function () {
            $.ajax({
                url: routeDuplicate,
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