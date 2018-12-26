$(document).ready(function () {
    /*
     M = Monday
     T = Tuesday
     W = Wednesday
     R = Thursday
     F = Friday
     S = Saturday
     U = Sunday (That's right, U for Sunday).*/

    var needUpdate = false;
    var formSearch = $('#search-accomodation');
    var contract = null;
    var searched = false;

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
            searched = false;
            fillContract(value);
            var url = window.location.href;
            if (url.indexOf("?") > 0) {
                var updatedUri = url.substring(0, url.indexOf("?"));
                window.history.replaceState({}, document.title, updatedUri);
            }
        }
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
            if (xhr.status != '200') {
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

    $('.btn-import').on('click', function(e) {
        e.preventDefault();
        if (contract.markets.length <= 1) {
            toastr['info']("There are no price rates available to import.", "Information");
        }
        else {
            swal({
                title: 'Confirmation',
                text: 'Are you sure you want to import the costs from the ' + $('#import-from option:selected').text() + ' price rate to the ' + $('#market option:selected').text() + ' price rate?',
                type: null,
                allowOutsideClick: false,
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonClass: "green",
                cancelButtonClass: "red",
                closeOnConfirm: true,
                closeOnCancel: true,
                confirmButtonText: "Accept",
                cancelButtonText: "Cancel"
            },
            function(isConfirm){
                if (isConfirm){
                    $.ajax({
                        url: routeImportPrice,
                        "type": "POST",
                        "data":  {
                            "contract-id": contract.id,
                            "price-rate": $('#import-from').val(),
                            "market-id": $('#market').val()
                        },
                        "beforeSend": function() {
                            App.showMask(true, $('.node-data'));
                        },
                        "complete": function(xhr, textStatus) {
                            App.showMask(false, $('.node-data'));
                            if (xhr.status != '200') {
                                toastr['error']("Please check your connection and try again.", "Error on loading the content");
                            }
                            else {
                                var response = $.parseJSON(xhr.responseText);
                                if (response.status == 'success') {
                                    $(".btn-search-submit").click();
                                    toastr['success'](response.message, "Success");
                                }
                                else {
                                    toastr['error'](response.message, "Error");
                                }
                            }
                        }
                    });
                }
            });
        }
    });

    $('.btn-search-submit').on('click', function(e) {
        e.preventDefault();
        if(contract == null) {
            toastr['error']('Invalid contract.', "Error");
        }
        else {
            var from = moment($('input[name=from]').datepicker("getDate")).format('DD.MM.YYYY');
            var to = moment($('input[name=to]').datepicker("getDate")).format('DD.MM.YYYY');
            var market = $('#market').val();
            var roomTypes = [];
            $('[name="room-selected"]:checked').each(function () {
                roomTypes.push($(this).val());
            });
            var rows = [];
            $('[name="row-selected"]:checked').each(function () {
                rows.push($(this).val());
            });
            $.ajax({
                "url": routeData,
                "type": "POST",
                "data": {
                    id: contract.id,
                    from:  from,
                    to: to,
                    market: market,
                    rooms: JSON.stringify(roomTypes),
                    rows: JSON.stringify(rows)
                },
                "beforeSend": function() {
                    App.showMask(true, formSearch);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formSearch);
                    if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        searched = true;
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            var table = response.table;
                            $('.result-container').html('');
                            $('.result-container').html('' +
                                '<div class="note note-info">'+
                                    '<p>Costing and pricing are all per person per night.</p>' +
                                '</div>'
                            );
                            $('.result-container').append(table);
                            renderTable(response.from, response.to, contract);
                        }
                        else {
                            toastr['error'](response.message, "Error");
                        }
                    }
                }
            });
        }
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
        //$('#modal-setting :input[name=count-board-type]').val(countSelectedRecords(tableShareRoomType));
    });

    tableShareRoomType.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass("active");
        //alert( tableShareRoomType.api().rows('.active').data().length +' row(s) selected' );
        //$('#modal-setting :input[name=count-room-type]').val(countSelectedRecords(tableShareRoomType));
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

    $('#modal-change :input[name="change-room"]').select2({
        width: "off"
    });

    $('#modal-import :input[name="select-room"]').select2({
        width: "off"
    });

    function fillContract(c) {
        var roomTypes = c.room_types;
        var measures = c.measures;
        var markets = c.markets;
        var contract = c;
        var chain = contract.hotel.hotel_chain != null ? contract.hotel.hotel_chain.name : '';
        var status = contract.active == 1 ? 'Enabled' : 'Disabled';
        $("#search-accomodation :input[name=hotel]").val(contract.hotel.name);
        $("#search-accomodation :input[name=hotel-chain]").val(chain);
        $("#search-accomodation :input[name=status]").val(status);
        $("#search-accomodation :input[name=period]").val(moment(contract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY') + ' - ' + moment(contract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
        $('.result-container').html('');
        $('.measures-list').html('');
        $.each(measures, function (i, item) {
            var measure =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" name="row-selected" checked value="' + measures[i].id + '"> ' + measures[i].name +
                '<span></span>' +
                '</label>';
            $('.measures-list').append(measure);
        });

        $('#market').empty();
        $.each(markets, function (i, item) {
            var option = '<option value="' + markets[i].id + '"> ' + markets[i].name + '</option>';
            $('#market').append(option);
        });

        updateImport(markets);

        $('.room-types-list').html('');
        $('#modal-change :input[name="change-room"]').empty();
        $.each(roomTypes, function (i, item) {
            var roomType =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" name="room-selected" checked value="' + roomTypes[i].id + '"> ' + roomTypes[i].name +
                '<span></span>' +
                '</label>';
            $('.room-types-list').append(roomType);

            var option = '<option value="' + roomTypes[i].id + '" data-name="' + roomTypes[i].name + '"> ' + roomTypes[i].name + ' (' +
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
            console.log(endDate.format('DD.MM.YYYY'));
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

        $('.measures-container').html('');

        for (var i = 0; i < contract.measures.length; i++) {
            var html =
                '<div class="row">' +
                    '<div class="col-md-12">' +
                        '<div class="col-md-5 col-sm-5 col-xs-5">' +
                            '<div class="form-group">' +
                                '<label>' + contract.measures[i].name + '</label>' +
                                '<input type="text" class="form-control measure-input" name="' + contract.measures[i].code + '" readonly>' +
                            '</div>' +
                        '</div>';
            if (contract.measures[i].code == 'cost') {
                html +=
                        '<div class="col-md-7 col-sm-7 col-xs-7">' +
                            '<div class="mt-checkbox-inline">' +
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Set' +
                                    '<input type="checkbox" class="set" value="1" name="set-' + contract.measures[i].code + '" data-set="' + contract.measures[i].code + '" data-measure-id="' + contract.measures[i].id + '" />' +
                                    '<span></span>' +
                                '</label>' +
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Unset' +
                                    '<input type="checkbox" class="set" value="0" name="unset-cost" />' +
                                    '<span></span>' +
                                '</label>' +
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15"> Import' +
                                    '<input type="checkbox" class="set" value="0" name="import-cost" />' +
                                    '<span></span>' +
                                '</label>' +
                            '</div>' +
                        '</div>';
            }
            else {
                html +=
                        '<div class="col-md-7 col-sm-7 col-xs-7">' +
                            '<div class="mt-checkbox-inline">' +
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                                    '<input type="checkbox" class="set" value="" name="set-' + contract.measures[i].code + '" data-set="' + contract.measures[i].code + '" data-measure-id="' + contract.measures[i].id + '" />' +
                                    '<span></span>' +
                                '</label>' +
                            '</div>' +
                        '</div>';
            }
            html +=
                    '</div>' +
                '</div>';
            $('.measures-container').append(html);
            $('input[name="set-' + contract.measures[i].code + '"]').change(function() {
                var name = $(this).attr('data-set');
                if($(this).is(":checked")) {
                    $('input[name=' + name + ']').prop('readonly', '');
                    $(this).val($(this).attr('data-measure-id'));
                }
                else {
                    $(this).val('0');
                    $('input[name=' + name + ']').prop('readonly', true);
                    //$('input[name=' + name + ']').val('');
                }
            });

            $('.cancel-import').on('click', function(e) {
                $('input[name="import-cost"]').prop('checked', '');
                $('input[name="import-cost"]').val(0);
            });

            $('input[name=import-cost]').change(function () {
                if ($(this).is(':checked')) {
                    //$('#modal-import :input[name="select-room"]').val($('#modal-setting :input[name="room-type-id"]').val()).trigger("change");
                    $('input[name="set-cost"]').prop('checked', '');
                    $('input[name="unset-cost"]').prop('checked', '');
                    var room = $('#modal-setting .room-name-header').html();
                    $('#modal-import .room-name-header').html(room);
                    $('input[name=add-value]').prop('checked', '');
                    $('input[name="rate_fee_value"]').attr('disabled', 'disabled').val('');
                    $('input[name="rate_percent_value"]').attr('disabled', 'disabled').val('');
                    $('input[data-target="rate_fee_value"]').prop('checked', true).attr('disabled', 'disabled');
                    $('input[data-target="rate_percent_value"]').attr('disabled', 'disabled');
                    $('#modal-import').modal('show');
                }
            });
        }

        $('input[name="unset-cost"]').change(function() {
            if($(this).is(":checked")) {
                $('.measure-input').prop('readonly', true);
                $('.measure-input').val('');
                $('input[name^=set-]').prop('checked', '');
                $('input[name="import-cost"]').prop('checked', '');
                //$('input[name^=set-]').attr('onclick', 'return false;');
                formSetting.validate();
                $('input[name="cost"]').rules('remove', 'required');
            }
            else {
                $('.measure-input').prop('readonly', '');
                $('input[name^=set-]').attr('onclick', '');
                formSetting.validate();
                $('input[name="cost"]').rules('add', 'required');
            }
        });

        $('input[name="set-cost"]').change(function() {
            if($(this).is(":checked")) {
                $('input[name="cost"]').rules('add', 'required');
                $('input[name="unset-cost"]').prop('checked', '');
                $('input[name="import-cost"]').prop('checked', '');
            }
        });
    }

    function updateShare() {
        $('.share-rooms').hide();
        var roomTypes = contract.room_types;
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

    function updateImport(markets) {
        $('#import-from').empty();
        $('#import-from').removeAttr('disabled');
        $('.btn-import').removeAttr('disabled');

        if (markets.length > 1) {
            $("#market option:not(:selected)").each(function(i){
                var option = '<option value="' + $(this).val() + '"> ' + $(this).text() + '</option>';
                $('#import-from').append(option);
            });
        }
        else {
            $('#import-from').attr('disabled', 'disabled');
            $('.btn-import').attr('disabled', 'disabled');
        }
    }

    function updateImportCost(roomTypes) {
        $('#modal-import :input[name="select-room"]').empty();
        $.each(roomTypes, function (i, item) {
            var option = '<option value="' + roomTypes[i].id + '" data-name="' + roomTypes[i].name + '"> ' + roomTypes[i].name + ' (' +
                'Max Pax: ' + roomTypes[i].max_pax +
                ', Min Pax: ' + roomTypes[i].min_pax +
                ', Max AD: ' + roomTypes[i].max_adult +
                ', Min AD: ' + roomTypes[i].min_adult +
                ', Max CH: ' + roomTypes[i].max_children +
                ', Min CH: ' + roomTypes[i].min_children +
                ', Max INF: ' + roomTypes[i].max_infant +
                ', Min INF: ' + roomTypes[i].min_infant +
                ')' + '</option>';

            //if(roomTypes[i].id != $('#modal-setting :input[name="room-type-id"]').val()) {
                $('#modal-import :input[name="select-room"]').append(option);
            //}
        });
    }

    function renderTable(from, to, contract) {
        $('.item-setting').on('click', function() {
            formSetting.validate();
            $('input[name="cost"]').rules('add', 'required');
            formSetting.validate().resetForm();
            formSetting[0].reset();
            var room = $(this).parents('table').find('th:first').html();
            $('#modal-setting .room-name-header').html(room);

            var date = $(this).attr('data-date');
            $('#modal-setting :input[name=setting-from]').datepicker("setDate" , new Date(moment(date, 'YYYY-MM-DD')));
            $('#modal-setting :input[name=setting-to]').datepicker("setDate" , new Date(moment(date, 'YYYY-MM-DD')));

            $('#modal-setting .measures-container :input').prop('readonly', true);
            $('#modal-setting .measures-container :input').prop('checked', '');
            var measure = $(this).parents('tr').find('td:first').attr('data-measure-code');
            $('#modal-setting :input[name=' + measure + ']').prop('readonly', false);
            $('#modal-setting :input[data-set=' + measure + ']').prop('checked', 'checked');
            $('#modal-setting :input[data-set=' + measure + ']').val($('#modal-setting :input[data-set=' + measure + ']').attr('data-measure-id'));

            $("#modal-setting :input[name=contract-id]").val(contract.id);
            $("#modal-setting :input[name=room-type-id]").val($(this).attr('data-room-type-id'));
            $("#modal-setting :input[name=market-id]").val($(this).attr('data-market-id'));

            for (var i = 0; i < contract.measures.length; i++) {
                var date = $(this).attr('data-date');
                var measureId = contract.measures[i].id;
                var value = $(this).parents('table').find('td[data-date="' + date + '"][data-measure-id="' + measureId + '"]').html();
                $('#modal-setting :input[name="' + contract.measures[i].code + '"]').val(value);
            }

            $('#modal-setting .range-optional').each(function() {
                $(this).remove();
            });

            updateImport(contract.markets);
            updateShare();
            updateImportCost(contract.room_types);

            $('#modal-setting').modal('show');
        });
    }

    function getSelectedRows(table) {
        var rows = [];
        var selected = tableShareRoomType.api().rows('.active').data();
        for (var i = 0; i < selected.length; i++) {
            rows.push(selected[i][1]);
        }
        return rows;
    }

    var formSetting = $('#form-setting');
    formSetting.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            "cost" : {
                required: true
            },
            "setting-from" : {
                required: true
            },
            "setting-to" : {
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
                //"data": formSetting.serialize(),
                "beforeSend": function() {
                    App.showMask(true, formSetting);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formSetting);
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
                                formSetting[0].reset();
                            }
                            else {
                                $('#modal-setting :input[name="cost"]').val('');
                                $('#modal-setting :input[name="price"]').val('');
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

    $('.add-row').on('click', function(e) {
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

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');

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

    $('.cancel-form').on('click', function(e) {
        if(needUpdate) {
            $(".btn-search-submit").click();
            needUpdate = false;
        }
    });

    $('#market').change(function() {
        if(searched) {
            $('.btn-search-submit').click();
        }
        updateImport(contract.markets);
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
        //var name = $('#modal-change :input[name="change-room"]').select2('data')[0]['text'];
        var name = $('#modal-change :input[name="change-room"]').select2().find(":selected").data("name");
        $('#modal-setting :input[name="room-type-id"]').val(id);
        $('#modal-setting .room-name-header').html(name);
        $('.cancel-change').click();
        updateShare();
        updateImportCost(contract.room_types);
        $('#modal-setting :input[name=share]').prop('checked', '');
        $('#modal-setting :input[name=share]').val(0);
    });

    $('#modal-import [id=rate_fee_value]').TouchSpin({
        min: -1000000000,
        max: 1000000000,
        stepinterval: 50,
        decimals: 2,
        maxboostedstep: 10000000,
        prefix: '$'
    });

    $('#modal-import [id=rate_percent_value]').TouchSpin({
        min: -1000000000,
        max: 1000000000,
        step: 1,
        decimals: 2,
        /*boostat: 5,*/
        maxboostedstep: 10000000,
        postfix: '%'
    });

    $('input[name=add-value]').on('click', function(e) {
        if ($(this).is(':checked')) {
            $('input[name="rate_fee_value"]').removeAttr('disabled');
            $('input[data-target="rate_fee_value"]').removeAttr('disabled');
            $('input[data-target="rate_percent_value"]').removeAttr('disabled');

        }
        else {
            $('input[name="rate_fee_value"]').attr('disabled', 'disabled').val('');
            $('input[name="rate_percent_value"]').attr('disabled', 'disabled').val('');
            $('input[data-target="rate_fee_value"]').attr('disabled', 'disabled').prop('checked', true);
            $('input[data-target="rate_percent_value"]').attr('disabled', 'disabled');
        }
    });

    $('input[name="rate_type"]').change(function() {
        var type = $(this).val();
        if (type == 1) {
            $('input[name="rate_fee_value"]').attr('disabled', 'disabled').val('');
            $('input[name="rate_percent_value"]').removeAttr('disabled');
        }
        else {
            $('input[name="rate_percent_value"]').attr('disabled', 'disabled').val('');
            $('input[name="rate_fee_value"]').removeAttr('disabled');
        }
    });


    var formImport = $('#form-import');
    formImport.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        /*rules: {
            "cost" : {
                required: true
            },
            "setting-from" : {
                required: true
            },
            "setting-to" : {
                required: true
            }
        },*/
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
            var contractId = $('#modal-setting :input[name="contract-id"]').val();
            var roomTypeId = $('#modal-setting :input[name="room-type-id"]').val();
            var marketId = $('#modal-setting :input[name="market-id"]').val();
            var formData = new FormData(formImport[0]);

            formData.append('contract-id', contractId);
            formData.append('room-type-id', roomTypeId);
            formData.append('market-id', marketId);
            $.ajax({
                "url": routeImportCost,
                "type": "POST",
                "data": formData,
                "contentType": false,
                "processData": false,
                //"data": formSetting.serialize(),
                "beforeSend": function() {
                    App.showMask(true, formImport);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formImport);
                    if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            toastr['success'](response.message, "Success");
                            needUpdate = true;
                            $(form).find("button.cancel-import").click();
                            formImport[0].reset();
                        }
                        else {
                            toastr['error'](response.message, "Error");
                        }
                    }
                }
            });
        }
    });
});
