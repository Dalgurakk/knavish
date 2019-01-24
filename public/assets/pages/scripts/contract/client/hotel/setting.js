$(document).ready(function () {
    var needUpdate = false;
    var formSearch = $('#search-accomodation');
    var contract = null;
    var operateMeasures = null;

    function searchFormat(repo) {
        if (repo.loading) return repo.text;
        var markup =
            "<div class=''>" +
            "<div class=''>" + repo.name + "</div>"+
            "</div>";
        return markup;
    }

    function searchFormatSelection(repo) {
        return repo.name;
    }

    $("#search-accomodation :input[name=contract]").select2({
        width: "off",
        ajax: {
            url: routeSearch,
            "type": "POST",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function(data, page) {
                return {
                    results: data
                };
            },
            "complete": function(xhr, textStatus) {
                if (xhr.status == '419') {
                    location.reload(true);
                }
            },
            cache: true
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        minimumInputLength: 3,
        templateResult: searchFormat,
        templateSelection: searchFormatSelection
    });

    $("#search-accomodation :input[name=contract]").on('select2:select select2:unselect', function (e) {
        var value = e.params.data;
        if(value.selected) {
            contract = value;
            fillContract(value);
            var url = window.location.href;
            if (url.indexOf("?") > 0) {
                var updatedUri = url.substring(0, url.indexOf("?"));
                window.history.replaceState({}, document.title, updatedUri);
            }
        }
    });

    $('#modal-setting :input[name=setting-from]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var start = $(this).val();
        var end = $('#modal-setting :input[name=setting-to]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
            $('#modal-setting :input[name=setting-to]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
        }
    });

    $('#modal-setting :input[name=setting-to]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var end = $(this).val();
        var start = $('input[name="setting-from"]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
            $('input[name="setting-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
        }
    });

    $('.add-row-setting').on('click', function(e) {
        e.preventDefault();
        var time = $.now();
        var range =
            '<div class="range range-optional" data="' + time + '">' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>From</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="setting-from-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>To</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="setting-to-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2 col-sm-2 col-xs-2">' +
            '<div class="form-group">' +
            '<a class="btn red btn-outline delete-row" href="#" data="' + time + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>';
        $('.range-container').append(range);

        $('input[name="setting-from-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var start = $(this).val();
            var end = $('input[name="setting-to-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (start != 0 && start != '' && (end == 0 || end == '' || moment(endDate).isBefore(startDate))) {
                $('input[name="setting-to-' + time + '"]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
            }
        });

        $('input[name="setting-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var end = $(this).val();
            var start = $('input[name="setting-from-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (end != 0 && end != '' && (start == 0 || start == '' || moment(endDate).isBefore(startDate))) {
                $('input[name="setting-from-' + time + '"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
            }
        });

        var startDate = moment(contract.hotel_contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.hotel_contract.valid_to, 'YYYY-MM-DD');

        $('input[name="setting-from-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name="setting-from-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));
        $('input[name="setting-to-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name="setting-to-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));

        formSetting.validate();
        $('input[name="setting-from-' + time + '"]').rules('add', 'required');
        $('input[name="setting-to-' + time + '"]').rules('add', 'required');

        $('.delete-row[data="' + time + '"]').on('click', function(e) {
            $('.range[data="' + time + '"]').remove();
            e.preventDefault();
        });
    });

    $.ajax({
        "url": routeContract,
        "type": "POST",
        "data": {
            contractId: contractId
        },
        "beforeSend": function() {
            App.showMask(true, formSearch);
        },
        "complete": function(xhr, textStatus) {
            App.showMask(false, formSearch);
            if (xhr.status == '419') {
                location.reload(true);
            }
            else if (xhr.status != '200') {
                toastr['error']("Please check your connection and try again.", "Error on loading the content");
            }
            else {
                var response = JSON.parse(xhr.responseText);
                contract = response.data;
                if (contract == null) {
                    toastr['warning'](response.message, "Warning");
                }
                else {
                    $("#search-accomodation :input[name=contract]").next().find(".select2-selection__rendered").html(contract.name + '<span class="select2-selection__placeholder"></span>');
                    fillContract(contract);
                    $('.filter-content').show();
                }
            }
        }
    });

    $('#modal-change :input[name="change-room"]').select2({
        width: "off"
    });

    var tableShareRoomType = $('#modal-setting .table-room-type').dataTable({
        "sDom": "tip",
        "autoWidth": false,
        "columnDefs": [
            { 'orderable': false, "className": "dt-center", 'targets': [0], "width": "20%" },
            { 'visible': false, 'targets': [1] }
        ],
        "order": [[ 3, "asc" ]],
        "lengthMenu": [[-1], ["All"]],
        "pageLength": 10
    });

    tableShareRoomType.find('.group-checkable').change(function () {
        var set = jQuery(this).attr("data-set");
        var checked = jQuery(this).is(":checked");
        jQuery(set).each(function () {
            if (checked) {
                $(this).prop("checked", true);
                $(this).parents('tr').addClass("active");
            } else {
                $(this).prop("checked", false);
                $(this).parents('tr').removeClass("active");
            }
        });
    });

    tableShareRoomType.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass("active");
    });

    $('input[name=search-code]').on('keyup', function() {
        tableShareRoomType.api()
            .columns(2).search($('input[name="search-code"]').val())
            .columns(3).search($('input[name="search-name"]').val())
            .draw();
    });

    $('input[name=search-name]').on('keyup', function() {
        tableShareRoomType.api()
            .columns(2).search($('input[name="search-code"]').val())
            .columns(3).search($('input[name="search-name"]').val())
            .draw();
    });

    $('input[name=share]').change(function () {
        if ($(this).is(':checked'))
            $('.share-rooms').show();
        else
            $('.share-rooms').hide();
    });

    $('input[name=change]').change(function () {
        if ($(this).is(':checked')) {
            $('#modal-change :input[name="change-room"]').val($('#modal-setting :input[name="room-type-id"]').val()).trigger("change");
            $('#modal-change').modal('show');
        }
    });

    $('.cancel-change').on('click', function(e) {
        $('#modal-setting :input[name=change]').prop('checked', '');
        $('#modal-setting :input[name=change]').val(0);
    });

    $('.accept-change').on('click', function(e) {
        var id = $('#modal-change :input[name="change-room"]').val();
        var rooms = contract.hotel_contract.room_types;
        var room = null;
        for (var i = 0; i < rooms.length; i++) {
            if (id == rooms[i].id) {
                room = rooms[i];
                break;
            }
        }
        $('#modal-setting :input[name="room-type-id"]').val(id);
        $('#modal-setting .room-name-header').html(room.code + ': ' + room.name);
        $('.cancel-change').click();
        updateShare();
        $('#modal-setting :input[name=share]').prop('checked', '');
        $('#modal-setting :input[name=share]').val(0);
    });

    $('.btn-search-submit').on('click', function(e) {
        e.preventDefault();
        if(contract == null) {
            toastr['error']('Invalid contract.', "Error");
        }
        else {
            var from = moment($('input[name=from]').datepicker("getDate")).format('DD.MM.YYYY');
            var to = moment($('input[name=to]').datepicker("getDate")).format('DD.MM.YYYY');
            var roomTypes = [];
            $('[name="room-selected"]:checked').each(function () {
                roomTypes.push($(this).val());
            });
            var rows = [];
            $('.row-selected:checked').each(function () {
                rows.push($(this).val());
            });
            $.ajax({
                "url": routeData,
                "type": "POST",
                "data": {
                    id: contract.id,
                    from:  from,
                    to: to,
                    rooms: JSON.stringify(roomTypes),
                    rows: JSON.stringify(rows)
                },
                "beforeSend": function() {
                    App.showMask(true, formSearch);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formSearch);
                    if (xhr.status == '419') {
                        location.reload(true);
                    }
                    else if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            var table = response.table;
                            $('.result-container').html('');
                            $('.result-container').html('' +
                                '<div class="note note-info">'+
                                '<p>Costing and pricing are all per person per night.</p>' +
                                '</div>'
                            );
                            $('.result-container').append(table);//console.log(contract);
                            operateMeasures = response.operateMeasures;
                            operateTable(response.from, response.to, contract);
                        }
                        else {
                            toastr['error'](response.message, "Error");
                        }
                    }
                }
            });
        }
    });

    function getSelectedRows(table) {
        var rows = [];
        var selected = tableShareRoomType.api().rows('.active').data();
        for (var i = 0; i < selected.length; i++) {
            rows.push(selected[i][1]);
        }
        return rows;
    }

    jQuery.validator.addMethod("validDate", function(value, element) {
        return this.optional(element) || moment(value,"DD.MM.YYYY",true).isValid();
    }, "Invalid date, use dd.mm.yyyy.");

    var formSetting = $('#form-setting');
    formSetting.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            "setting-from" : {
                required: true,
                validDate: true
            },
            "setting-to" : {
                required: true,
                validDate: true
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
            var count = 0;
            $('#modal-setting :input[name^="set-"]').each(function(i){
                if ($(this).prop('checked') == true)
                    count ++;
            });
            $('#modal-setting :input[name^="unset-"]').each(function(i){
                if ($(this).prop('checked') == true)
                    count ++;
            });
            if (count == 0) {
                $(form).find("button.cancel-form").click();
            }
            else {
                var formData = new FormData(formSetting[0]);
                var market = $('#market').val();
                var ranges = [];
                $('#modal-setting .range').each(function () {
                    var obj = {
                        from : $(this).find('input[name^="setting-from"]').val(),
                        to : $(this).find('input[name^="setting-to"]').val()
                    };
                    ranges.push(obj);
                });
                var rooms = getSelectedRows(tableShareRoomType);
                var selected = $('input[name="room-type-id"]').val();
                rooms.push(parseInt(selected));
                formData.append('room-types', JSON.stringify(rooms));
                formData.append('ranges', JSON.stringify(ranges));
                formData.append('market', market);
                $.ajax({
                    "url": routeSave,
                    "type": "POST",
                    "data": formData,
                    "contentType": false,
                    "processData": false,
                    "beforeSend": function() {
                        App.showMask(true, formSetting);
                    },
                    "complete": function(xhr, textStatus) {
                        App.showMask(false, formSetting);
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
                                if (option == 'accept') {
                                    $(form).find("button.cancel-form").click();
                                    formSetting[0].reset();
                                }
                            }
                            else {
                                toastr['error'](response.message, "Error");
                            }
                        }
                    }
                });
            }
        }
    });

    $('.cancel-form').on('click', function(e) {
        if(needUpdate) {
            $(".btn-search-submit").click();
            needUpdate = false;
        }
    });

    function fillContract(c) {
        var roomTypes = c.hotel_contract.room_types;
        var measures = c.hotel_contract.measures;
        var contract = c.hotel_contract;
        var client = c.client;
        var status = contract.active == 1 ? 'Enabled' : 'Disabled';
        $("#search-accomodation :input[name=hotel]").val(contract.hotel.name);
        $("#search-accomodation :input[name=status]").val(status);
        $("#search-accomodation :input[name=client]").val(client.name);
        $("#search-accomodation :input[name=period]").val(moment(contract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY') + ' - ' + moment(contract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
        $('.result-container').html('');
        $('.measures-list').html('');
        $.each(measures, function (i, item) {
            /*var measure =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" name="row-selected" checked value="' + measures[i].id + '"> ' + measures[i].name +
                '<span></span>' +
                '</label>';*/
            var measure =
                '<div class="row">' +
                '<div class="col-md-5">' +
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" class="row-selected" checked value="' + measures[i].id + '"> ' + measures[i].name +
                '<span></span>' +
                '</label>' +
                '</div>';
            if (measures[i].code == 'cost' || measures[i].code == 'price' || measures[i].code == 'allotment') {
                measure +=
                '<div class="col-md-7">' +
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" class="row-expanded" value="' + measures[i].code + '"> Expand ' + measures[i].name +
                '<span></span>' +
                '</label>' +
                '</div>';
            }
            measure += '</div>';

            $('.measures-list').append(measure);
        });

        $('.room-types-list').html('');
        $.each(roomTypes, function (i, item) {
            var roomType =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" name="room-selected" checked value="' + roomTypes[i].id + '"> ' + roomTypes[i].code + ': ' + roomTypes[i].name +
                '<span></span>' +
                '</label>';
            $('.room-types-list').append(roomType);

            var option = '<option value="' + roomTypes[i].id + '" data-name="' + roomTypes[i].name + '"> ' +
                roomTypes[i].code + ': ' +
                roomTypes[i].name + ' (' +
                'Max Pax: ' + roomTypes[i].max_pax +
                ', Min Pax: ' + roomTypes[i].min_pax +
                ', Max AD: ' + roomTypes[i].max_adult +
                ', Min AD: ' + roomTypes[i].min_adult +
                ', Max CH: ' + roomTypes[i].max_children +
                ', Min CH: ' + roomTypes[i].min_children +
                ', Max INF: ' + roomTypes[i].max_infant +
                ', Min INF: ' + roomTypes[i].min_infant +
                ')' + '</option>';
            $('#modal-change :input[name="change-room"]').append(option);
        });

        $('input[name=from]').datepicker( "destroy" );
        $('input[name=to]').datepicker( "destroy" );

        $('.datepicker-from-container').html('');
        $('.datepicker-to-container').html('');

        var html =
            '<label>From</label>' +
            '<div class="form-group">' +
            '<div class="input-icon">' +
            '<i class="fa fa-calendar"></i>' +
            '<input class="form-control datepicker" name="from"> </div>' +
            '</div>';
        $('.datepicker-from-container').append(html);
        html =
            '<label>To</label>' +
            '<div class="form-group">' +
            '<div class="input-icon">' +
            '<i class="fa fa-calendar"></i>' +
            '<input class="form-control datepicker" name="to"> </div>' +
            '</div>';
        $('.datepicker-to-container').append(html);

        $(".datepicker").datepicker({
            format: "MM yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
            orientation: "bottom"
        });

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');
        var currentDate = moment();
        var tempStart = currentDate;
        var tempEnd = currentDate;

        if (currentDate.isSameOrBefore(endDate) && currentDate.isSameOrAfter(startDate)){
            tempStart = moment(currentDate).startOf('month');
            tempEnd = moment(currentDate).endOf('month');
        }
        else if (currentDate.isBefore(startDate)) {
            tempStart = moment(startDate).startOf('month');
            tempEnd = moment(startDate).endOf('month');
        }
        else if (currentDate.isAfter(endDate)) {
            tempStart = moment(endDate).startOf('month');
            tempEnd = moment(endDate).endOf('month');
        }

        if(tempStart.isBefore(startDate)) {
            tempStart = startDate;
        }
        if(tempEnd.isAfter(endDate)) {
            tempEnd = endDate;
        }

        $('input[name=from]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name=from]').datepicker( "setEndDate" , new Date(endDate));
        $('input[name=to]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name=to]').datepicker( "setEndDate" , new Date(endDate));

        $('input[name=from]').datepicker( "setDate" , new Date(tempStart));
        $('input[name=to]').datepicker( "setDate" , new Date(tempEnd));

        $('#modal-setting :input[name=setting-from]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-setting :input[name=setting-from]').datepicker( "setEndDate" , new Date(endDate));
        $('#modal-setting :input[name=setting-to]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-setting :input[name=setting-to]').datepicker( "setEndDate" , new Date(endDate));
    }

    function updateShare() {
        $('.share-rooms').hide();
        var roomTypes = contract.hotel_contract.room_types;
        if (roomTypes.length > 1) {
            tableShareRoomType.api().clear().draw();
            var check =
                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                '<input type="checkbox" class="checkboxes" value="1" />' +
                '<span></span>' +
                '</label>';
            for (var i = 0; i < roomTypes.length; i++) {
                if (roomTypes[i].id != $('input[name="room-type-id"]').val()) {
                    tableShareRoomType.api().row.add([
                        check,
                        roomTypes[i].id,
                        roomTypes[i].code,
                        roomTypes[i].name
                    ]).draw( false );
                    tableShareRoomType.api().columns.adjust().draw();
                }
            }
            $('.share-container').show();
        }
        else {
            $('.share-container').hide();
        }
    }

    function operateTable(from, to, contract) {
        var roomTypes = contract.hotel_contract.room_types;
        var contractMeasures = contract.hotel_contract.measures;
        $('.item-setting').on('click', function() {
            var haasStopSale = false;
            for (var i = 0; i < contractMeasures.length; i++) {
                if (contractMeasures[i].code == 'stop_sale') {
                    haasStopSale = true;
                    break;
                }
            }
            if (!haasStopSale)
                return false;

            $('input[name^="cost"]').each(function(i) {
                $(this).rules('remove', 'required');
            });
            $('input[name^="price"]').each(function(i) {
                $(this).rules('remove', 'required');
            });
            $('input[name^="allotment"]').each(function(i) {
                $(this).rules('remove', 'required');
            });
            $('input[name^="release"]').each(function(i) {
                $(this).rules('remove', 'required');
            });
            $('input[name^="stop_sale"]').each(function(i) {
                $(this).rules('remove', 'required');
            });
            $('#modal-setting .expand').each(function () {
                $(this).click();
            });

            var selectedRoomId = $(this).attr('data-room-type-id');
            $("#modal-setting :input[name=room-type-id]").val(selectedRoomId);
            var room = null;
            var measures = operateMeasures;
            for (var j = 0; j < roomTypes.length; j++) {
                if (roomTypes[j].id == selectedRoomId) {
                    room = roomTypes[j];
                    break;
                }
            }
            $('.cost-price-container').html('');
            $('.measures-container').html('');
            for (var i = 0; i < measures.length; i++) {
                if (measures[i].code == 'stop_sale') {
                    var html =
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<div class="col-md-5 col-sm-5 col-xs-5">' +
                        '<div class="form-group">' +
                        '<label>' + measures[i].name + '</label>' +
                        '<select id="select-stop-sale" class="form-control measure-input" name="' + measures[i].code + '" readonly="" disabled="disabled">' +
                        '<option value="0">No</option>' +
                        '<option value="1">Yes</option>' +
                        '</select>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-7 col-sm-7 col-xs-7">' +
                        '<div class="mt-checkbox-inline">' +
                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                        '<input type="checkbox" class="set" value="" name="set-' + measures[i].code + '" data-set="' + measures[i].code + '" data-measure-id="' + measures[i].id + '" />' +
                        '<span></span>' +
                        '</label>' +
                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Use From The Provider' +
                        '<input type="checkbox" class="set" value="0" name="unset-' + measures[i].code + '" data-unset="' + measures[i].code + '" data-measure-id="' + measures[i].id + '"/>' +
                        '<span></span>' +
                        '</label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    $('.measures-container').append(html);
                    $('#select-stop-sale').val(0).change();
                    $('input[name="set-' + measures[i].code + '"]').change(function() {
                        var code = $(this).attr('data-set');
                        if($(this).is(":checked")) {
                            $('#select-stop-sale').removeAttr('readonly', '');
                            $('input[name="unset-' + code + '"]').prop('checked', '');
                            $('#select-stop-sale').removeAttr('disabled');
                            $(this).val(1);
                        }
                        else {
                            $('#select-stop-sale').attr('readonly', true);
                            $('#select-stop-sale').attr('disabled', 'disabled');
                        }
                    });
                    $('input[name="unset-' + measures[i].code + '"]').change(function() {
                        var code = $(this).attr('data-unset');
                        if($(this).is(":checked")) {
                            $('input[name=' + code + ']').prop('readonly', true);
                            $('input[name="set-' + code + '"]').prop('checked', '');
                            $(this).val($(this).attr('data-measure-id'));
                            $('#select-stop-sale').attr('disabled', 'disabled');
                        }
                        else {
                            $(this).val('0');
                        }
                    });
                }
                /*else if (measures[i].code != 'cost' && measures[i].code != 'price') {
                    var html =
                        '<div class="row">' +
                        '<div class="col-md-12">' +
                        '<div class="col-md-5 col-sm-5 col-xs-5">' +
                        '<div class="form-group">' +
                        '<label>' + measures[i].name + '</label>' +
                        '<input type="text" class="form-control measure-input" name="' + measures[i].code + '" readonly>' +
                        '</div>' +
                        '</div>' +
                        '<div class="col-md-7 col-sm-7 col-xs-7">' +
                        '<div class="mt-checkbox-inline">' +
                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                        '<input type="checkbox" class="set" value="" name="set-' + measures[i].code + '" data-set="' + measures[i].code + '" data-measure-id="' + measures[i].id + '" />' +
                        '<span></span>' +
                        '</label>' +
                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Use From The Provider' +
                        '<input type="checkbox" class="set" value="0" name="unset-' + measures[i].code + '" data-unset="' + measures[i].code + '" data-measure-id="' + measures[i].id + '"/>' +
                        '<span></span>' +
                        '</label>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    $('.measures-container').append(html);
                    $('input[name="set-' + measures[i].code + '"]').change(function() {
                        var code = $(this).attr('data-set');
                        if($(this).is(":checked")) {
                            $('input[name=' + code + ']').prop('readonly', '');
                            $('input[name=' + code + ']').rules('add', 'required');
                            $(this).val($(this).attr('data-measure-id'));
                            $('input[name="unset-' + code + '"]').prop('checked', '');
                            $(this).val(1);
                        }
                        else {
                            $('input[name=' + code + ']').prop('readonly', true);
                            $('input[name=' + code + ']').rules('remove', 'required');
                        }
                    });
                    $('input[name="unset-' + measures[i].code + '"]').change(function() {
                        var code = $(this).attr('data-unset');
                        if($(this).is(":checked")) {
                            $('input[name=' + code + ']').prop('readonly', true);
                            $('input[name=' + code + ']').rules('remove', 'required');
                            $('input[name="set-' + code + '"]').prop('checked', '');
                            $(this).val($(this).attr('data-measure-id'));
                        }
                        else {
                            $(this).val('0');
                        }
                    });
                }*/
            }

            formSetting.validate();
            formSetting.validate().resetForm();
            formSetting[0].reset();

            $('#modal-setting .room-name-header').html(room.code + ': ' + room.name);

            var date = $(this).attr('data-date');
            $('#modal-setting :input[name=setting-from]').datepicker("setDate" , new Date(moment(date, 'YYYY-MM-DD')));
            $('#modal-setting :input[name=setting-to]').datepicker("setDate" , new Date(moment(date, 'YYYY-MM-DD')));

            $('#modal-setting .measures-container :input').prop('readonly', true);
            $('#modal-setting .measures-container :input').prop('checked', '');

            var measure = $(this).parents('tr').find('td:first').attr('data-measure-code');
            //$('#modal-setting :input[data-set=' + measure + ']').click();

            $("#modal-setting :input[name=contract-id]").val(contract.hotel_contract.id);
            $("#modal-setting :input[name=contract-client-id]").val(contract.id);
            $("#modal-setting :input[name=market-id]").val($(this).attr('data-market-id'));

            var measures = operateMeasures;

            for (var i = 0; i < measures.length; i++) {
                var date = $(this).attr('data-date');
                var measureId = measures[i].id;
                var item = $(this).parents('table').find('td[data-date="' + date + '"][data-measure-id="' + measureId + '"]');
                var value = item.attr('data');
                if (measures[i].code == 'stop_sale' || measures[i].code == 'allotment' || measures[i].code == 'release') {
                    var fromProvider = item.attr('data-from-provider');
                    if (fromProvider == 1) {
                        $('#modal-setting :input[name=unset-' + measures[i].code + ']').click();
                    }
                    else {
                        $('#modal-setting :input[name=set-' + measures[i].code + ']').click();
                    }
                    if (measures[i].code == 'stop_sale') {
                        $('#select-stop-sale').val(value).change();
                    }
                    else {
                        $('#modal-setting :input[name="' + measures[i].code + '"]').val(value);
                    }
                }
            }

            $('#modal-setting .range-optional').each(function() {
                $(this).remove();
            });

            updateShare();

            $('#modal-setting').modal('show');
        });
        $('.measure-detail').on('click', function () {
            var parent = $(this).attr('data');
            if ($(this).hasClass('closed')) {
                $(this).removeClass('closed');
                $(this).addClass('opened');
                $(this).html('-');
                $('tr[data-parent="' + parent + '"]').removeClass('hidden');
            }
            else {
                $(this).removeClass('opened');
                $(this).addClass('closed');
                $(this).html('+');
                $('tr[data-parent="' + parent + '"]').addClass('hidden');
            }
        });
        $('.row-expanded:checked').each(function () {
            $('.measure-detail[data-measure="' + $(this).val() + '"]').click();
        });
    }
});
