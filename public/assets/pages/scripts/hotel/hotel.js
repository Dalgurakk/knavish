$(document).ready(function () {
    var needUpdate = false;
    var object = null;
    var inputSelected;
    var inputSelectedHidden;

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
    /*if ($.support.cors) {
     $.ajax({
     type: 'HEAD'
     }).fail(function () {
     $('<div class="alert alert-danger"/>')
     .text('Upload server currently unavailable - ' +
     new Date())
     .appendTo('#fileupload');
     });
     }*/

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
                    "url": routeLocationReadActive,
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
            "url": routeRead,
            "type": "POST",
            "complete": function(xhr, textStatus) {
                if (xhr.status == '419') {
                    location.reload(true);
                }
                else if (xhr.status != '200') {
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
            {data: 'chain', name: 'chain', orderable: false},
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
                "url": routeCreate,
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
        var hotel = data['object'];
        var country = hotel.country != null ? hotel.country.name : '';
        var state = hotel.state != null ? hotel.state.name : '';
        var city = hotel.city != null ? hotel.city.name : '';
        var hotelChain = hotel.hotel_chain != null ? hotel.hotel_chain.name : '';

        $('#modal-info :input[name=name]').val(hotel.name);
        $('#modal-info :input[name=country-text]').val(country);
        $('#modal-info :input[name=state-text]').val(state);
        $('#modal-info :input[name=city-text]').val(city);
        $('#modal-info :input[name=postal-code]').val(hotel.postal_code);
        $('#modal-info :input[name=address]').val(hotel.address);
        $('#modal-info :input[name=category]').barrating('readonly', true);
        var category = hotel.category != null ? hotel.category : '';
        $('#modal-info :input[name=category]').barrating('set', category);
        $('#modal-info :input[name=hotel-chain]').val(hotelChain);
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
        object = data['object'];
        var hotel = data['object'];
        var country = hotel.country != null ? hotel.country.name : '';
        var countryId = hotel.country != null ? hotel.country.id : '';
        var state = hotel.state != null ? hotel.state.name : '';
        var stateId = hotel.state != null ? hotel.state.id : '';
        var city = hotel.city != null ? hotel.city.name : '';
        var cityId = hotel.city != null ? hotel.city.id : '';
        var hotelChainId = hotel.hotel_chain_id != null ? hotel.hotel_chain_id : '';

        $('#modal-edit :input[name=id]').val(data['id']);
        $('#modal-edit :input[name=name]').val(hotel.name);
        $('#modal-edit :input[name=country-text]').val(country);
        $('#modal-edit :input[name=state-text]').val(state);
        $('#modal-edit :input[name=city-text]').val(city);
        $('#modal-edit :input[name=country-id]').val(countryId);
        $('#modal-edit :input[name=state-id]').val(stateId);
        $('#modal-edit :input[name=city-id]').val(cityId);
        $('#modal-edit :input[name=postal-code]').val(hotel.postal_code);
        $('#modal-edit :input[name=address]').val(hotel.address);
        var category = hotel.category != null ? hotel.category : '';
        $('#modal-edit :input[name=category]').barrating('set', category);
        $('#modal-edit :input[name=hotel-chain-id]').val(hotelChainId);
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

    $('#table tbody').on( 'click', '.dt-image', function (e) {
        var $form = $('#fileupload');
        $form.find(".files").empty();
        var data = table.row( $(this).parents('tr') ).data();
        var id = data['id'];
        $('#modal-image :input[name=id]').val(id);
        /*$form.fileupload('option', 'done').call($form, $.Event('done'), {result: {files: files}});*/
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            url: routeImages,
            dataType: 'json',
            data: { id: id },
            type: "POST",
            context: $('#fileupload')[0],
            "complete": function(xhr, textStatus) {
                requestDelete = null;
                App.showMask(false, formAdd);
                if (xhr.status == '419') {
                    location.reload(true);
                }
            }
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
        $('#search-section :input[name=hotel-chain]').val('');
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
            .columns('chain:name').search($('#search-section :input[name=hotel-chain]').val())
            .columns('active:name').search($('#search-section :input[name=active]').val())
            .draw();
    });

    $('.excel').on('click',function(){
        var query = {
            name: $('#search-section :input[name=name]').val(),
            active: $('#search-section :input[name=active]').val()
        };
        var url = routeExcel + "?" + $.param(query);
        window.location = url;
    });
});