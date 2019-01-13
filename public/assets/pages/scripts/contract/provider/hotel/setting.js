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

    $('#modal-import :input[name=import-from]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var start = $(this).val();
        var end = $('#modal-import :input[name=import-to]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
            $('#modal-import :input[name=import-to]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
        }
    });

    $('#modal-import :input[name=import-to]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var end = $(this).val();
        var start = $('input[name="import-from"]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
            $('input[name="import-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
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

    $('.btn-import').on('click', function(e) {
        e.preventDefault();
        if (contract.markets.length <= 1) {
            toastr['info']("There are no price rates available to import.", "Information");
        }
        else {
            swal({
                title: 'Confirmation',
                text: 'Are you sure you want to import the costs from the ' + $('#import-cost-from-rate option:selected').text() + ' price rate to the ' + $('#market option:selected').text() + ' price rate?',
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
                        url: routeImportCostFromPriceRate,
                        "type": "POST",
                        "data":  {
                            "contract-id": contract.id,
                            "price-rate-from": $('#import-cost-from-rate').val(),
                            "price-rate-to": $('#market').val()
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
                                    $(".btn-search-submit").click();
                                    toastr['success'](response.message, "Success");
                                }
                                else if (response.status == 'warning') {
                                    toastr['warning'](response.message, "Warning");
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
                    if (xhr.status == '419') {
                        location.reload(true);
                    }
                    else if (xhr.status != '200') {
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

        $('#modal-import :input[name=import-from]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-import :input[name=import-from]').datepicker( "setEndDate" , new Date(endDate));
        $('#modal-import :input[name=import-to]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-import :input[name=import-to]').datepicker( "setEndDate" , new Date(endDate));
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
        $('#import-cost-from-rate').empty();
        $('#import-cost-from-rate').removeAttr('disabled');
        $('.btn-import').removeAttr('disabled');

        if (markets.length > 1) {
            $("#market option:not(:selected)").each(function(i){
                var option = '<option value="' + $(this).val() + '"> ' + $(this).text() + '</option>';
                $('#import-cost-from-rate').append(option);
            });
        }
        else {
            $('#import-cost-from-rate').attr('disabled', 'disabled');
            $('.btn-import').attr('disabled', 'disabled');
        }
    }

    function updateImportCost(roomTypes) {
        $('#modal-import :input[name="select-room"]').empty();
        $.each(roomTypes, function (i, item) {
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

            //if(roomTypes[i].id != $('#modal-setting :input[name="room-type-id"]').val()) {
                $('#modal-import :input[name="select-room"]').append(option);
            //}
        });
    }

    var formUseAdult = $('#form-use-adult');
    formUseAdult.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
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
            $('.active-use-adult').each(function() {
                var measure = $(this).attr('data-set');
                if($(this).prop('checked')) {
                    var rate = $('input[name="' + measure + '_rate"]').val();
                    var type = $('#' + measure + '_type').val();
                    var bonus = '';
                    if (type == 1)
                        bonus = '(' + rate + '%)';
                    else
                        bonus = '($' + rate + ')';
                    $('[data-set="' + measure + '"][data-type="rate"]').val(rate);
                    $('[data-set="' + measure + '"][data-type="type"]').val(type);
                    $('.label_' + measure).html('From Adult ' + bonus);
                    $('input[name="' + measure + '"]').prop('readonly', true);
                    $('input[name="' + measure + '"]').rules('remove', 'required');
                    $('input[name="use-adult-' + measure + '"]').prop('checked', true);
                }
                else {
                    $('[data-set="' + measure + '"][data-type="rate"]').val('');
                    $('[data-set="' + measure + '"][data-type="type"]').val('');
                    $('.label_' + measure).html('From Adult');
                    $('input[name="' + measure + '"]').prop('readonly', false);
                    $('input[name="' + measure + '"]').rules('add', 'required');
                    $('input[name="use-adult-' + measure + '"]').prop('checked', false);
                }
            });
            $('.cancel-use-adult').click();
            return false;
        }
    });

    function renderTable(from, to, contract) {
        var roomTypes = contract.room_types;
        $('.item-setting').on('click', function() {
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
            var measures = contract.measures;
            for (var j = 0; j < roomTypes.length; j++) {
                if (roomTypes[j].id == selectedRoomId) {
                    room = roomTypes[j];
                    break;
                }
            }
            $('.measures-container').html('');
            for (var i = 0; i < measures.length; i++) {
                if (measures[i].code == 'cost') {
                    var html =
                        '<div class="row">' +
                            '<div class="col-md-12">' +
                                '<div class="col-md-5 col-sm-5 col-xs-5">' +
                                    '<div class="form-group">' +
                                        '<label>' + measures[i].name + ' Adult</label>' +
                                        '<input type="text" class="form-control measure-input" name="' + measures[i].code + '" readonly>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-7 col-sm-7 col-xs-7">' +
                                    '<div class="mt-checkbox-inline">' +
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                                            '<input type="checkbox" class="set" value="1" name="set-' + measures[i].code + '" data-set="' + measures[i].code + '" data-measure-id="' + measures[i].id + '" />' +
                                            '<span></span>' +
                                        '</label>' +
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Unset' +
                                            '<input type="checkbox" class="set" value="0" name="unset-cost" />' +
                                            '<span></span>' +
                                        '</label>' +
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15"> Import' +
                                            '<input type="checkbox" class="set" value="0" name="import-cost" />' +
                                            '<span></span>' +
                                        '</label>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                    $('.measures-container').append(html);
                    if (room != null && room.max_children > 0 && room.max_children <= 3) {
                        for (var j = 1; j <= room.max_children; j++) {
                            var htmlPlus =
                        '<div class="row">' +
                            '<div class="col-md-12">' +
                                '<div class="col-md-5 col-sm-5 col-xs-5">' +
                                    '<div class="form-group">' +
                                        '<label>' + measures[i].name + ' Children ' + j + '</label>' +
                                        '<input type="text" class="form-control measure-input" name="' + measures[i].code + '_children_' + j + '" readonly>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-7 col-sm-7 col-xs-7 label_contentainer" style="display: none;">' +
                                    '<div class="mt-checkbox-inline">' +
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"><p class="label_' + measures[i].code + '_children_' + j + '">From Adult</p>' +
                                            '<input type="checkbox" class="set use-adult" value="1" name="use-adult-' + measures[i].code + '_children_' + j + '" data-set="' + measures[i].code + '_children_' + j + '"/>' +
                                            '<span></span>' +
                                        '</label>' +
                                        '<input type="hidden" value="" name="use-adult-rate-' + measures[i].code + '_children_' + j + '" data-set="' + measures[i].code + '_children_' + j + '" data-type="rate"/>' +
                                        '<input type="hidden" value="" name="use-adult-type-' + measures[i].code + '_children_' + j + '" data-set="' + measures[i].code + '_children_' + j + '" data-type="type"/>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                            $('.measures-container').append(htmlPlus);
                        }
                    }

                    $('input[name=import-cost]').change(function () {
                        if ($(this).is(':checked')) {
                            $('input[name="set-cost"]').prop('checked', '');
                            $('input[name="unset-cost"]').prop('checked', '');
                            formImport.validate().resetForm();
                            formImport[0].reset();
                            var dateFrom = contract.valid_from;
                            var dateTo = contract.valid_to;
                            $('#modal-import :input[name=import-from]').datepicker("setDate" , new Date(moment(dateFrom, 'YYYY-MM-DD')));
                            $('#modal-import :input[name=import-to]').datepicker("setDate" , new Date(moment(dateTo, 'YYYY-MM-DD')));
                            $('#modal-import .room-name-header').html(room.code + ': ' + room.name);
                            $('input[name=add-value]').prop('checked', '');
                            $('input[name="rate_fee_value"]').attr('disabled', 'disabled').val('');
                            $('input[name="rate_percent_value"]').attr('disabled', 'disabled').val('');
                            $('input[data-target="rate_fee_value"]').prop('checked', true).attr('disabled', 'disabled');
                            $('input[data-target="rate_percent_value"]').attr('disabled', 'disabled');
                            $('.add-value-container').hide();
                            $('#modal-import .range-optional').each(function () {
                                $(this).remove();
                            });
                            $('#modal-import .expand').each(function () {
                                $(this).click();
                            });
                            $('#modal-import').modal('show');
                        }
                    });

                    $('.use-adult').on('click', function(e) {
                        e.preventDefault();
                        var dataSet = $(this).attr('data-set');
                        $('.from-adult-content').html('');
                        var id = $("#modal-setting :input[name=room-type-id]").val();
                        var rooms = contract.room_types;
                        var room = null;
                        for (var i = 0; i < rooms.length; i++) {
                            if (id == rooms[i].id) {
                                room = rooms[i];
                                break;
                            }
                        }
                        for (var i = 1; i <= room.max_children; i++) {
                            var html = '';
                            if (i > 1)
                                html = '<hr>';
                            html +=
                            '<div class="children-setting" style="margin-bottom: 10px;margin-top: 10px;">' +
                                '<div class="row">' +
                                    '<div class="col-md-12">' +
                                        '<div class="row">' +
                                            '<div class="col-md-12">' +
                                                '<label class=""> Cost Children ' + i + '</label>' +
                                            '</div>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="col-md-12" style="margin-top: 10px;">' +
                                        '<div class="row" style="margin-top: 5px;">' +
                                            '<div class="col-md-4">' +
                                                '<div class="form-group">' +
                                                    '<label>Rate</label>' +
                                                    '<input type="text" class="form-control use-adult-input-rate" name="cost_children_' + i + '_rate">' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                                '<label>Type</label>' +
                                                '<div class="form-group">' +
                                                    '<select class="form-control" name="cost_children_' + i + '_type" id="cost_children_' + i + '_type">' +
                                                        '<option value="1">Percent</option>' +
                                                        '<option value="2">Fee</option>' +
                                                    '</select>' +
                                                '</div>' +
                                            '</div>' +
                                            '<div class="col-md-4">' +
                                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="width: 100%; margin-top: 24px;"> Enable' +
                                                    '<input class="active-use-adult" type="checkbox" value="1" name="cost_children_' + i + '_active" data-set="cost_children_' + i + '"/>' +
                                                    '<span></span>' +
                                                '</label>' +
                                            '</div>' +
                                            /*'<div class="col-md-4">' +
                                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="width: 100%; margin-top: 24px;"> Update Related' +
                                                    '<input type="checkbox" value="1" name="cost_children_' + i + '_update_related"/>' +
                                                    '<span></span>' +
                                                '</label>' +
                                            '</div>' +*/
                                        '</div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>';
                            $('.from-adult-content').append(html);
                            $('input[name="cost_children_' + i + '_active"]').on('click', function() {
                                var rowPref = $(this).attr('data-set');
                                if ($(this).prop('checked')) {
                                    $('input[name="' + rowPref + '_rate"]').rules('add', 'required');
                                }
                                else {
                                    $('input[name="' + rowPref + '_rate"]').rules('remove', 'required');
                                }
                            });
                        }
                        $('.use-adult').each(function() {
                            $('.active-use-adult').each(function() {
                                var auxRow = $(this).attr('data-set');
                                $('input[name="' + auxRow + '_rate"]').rules('remove', 'required');
                                $('input[name="' + auxRow + '_active"]').rules('remove', 'required');
                                //$('input[name="' + auxRow + '_update_related"]').rules('remove', 'required');
                            });
                            var rowPref = $(this).attr('data-set');
                            var rate = $('[data-set="' + rowPref + '"][data-type="rate"]').val();
                            var type = $('[data-set="' + rowPref + '"][data-type="type"]').val();
                            if (rate != '' && type != '') {
                                $('input[name="' + rowPref + '_rate"]').val(rate);
                                $('#' + rowPref + '_type').val(type);
                                $('input[name="' + rowPref + '_active"]').prop('checked', true);
                            }
                        });
                        $('input[name="' + dataSet + '_rate"]').rules('add', 'required');
                        $('input[name="' + dataSet + '_active"]').prop('checked', true);
                        $('#modal-use-adult').modal('show');
                    });

                    $('.cancel-import').on('click', function(e) {
                        $('input[name="import-cost"]').prop('checked', '');
                        $('input[name="import-cost"]').val(0);
                    });
                }
                else if (measures[i].code == 'price') {
                    var html =
                        '<div class="row">' +
                            '<div class="col-md-12">' +
                                '<div class="col-md-5 col-sm-5 col-xs-5">' +
                                    '<div class="form-group">' +
                                        '<label>' + measures[i].name + ' Adult</label>' +
                                        '<input type="text" class="form-control measure-input" name="' + measures[i].code + '" readonly>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-7 col-sm-7 col-xs-7">' +
                                    '<div class="mt-checkbox-inline">' +
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                                            '<input type="checkbox" class="set" value="1" name="set-' + measures[i].code + '" data-set="' + measures[i].code + '" data-measure-id="' + measures[i].id + '" />' +
                                            '<span></span>' +
                                        '</label>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                    $('.measures-container').append(html);
                    if (room != null && room.max_children > 0 && room.max_children <= 3) {
                        for (var j = 1; j <= room.max_children; j++) {
                            var htmlPlus =
                        '<div class="row">' +
                            '<div class="col-md-12">' +
                                '<div class="col-md-5 col-sm-5 col-xs-5">' +
                                    '<div class="form-group">' +
                                        '<label>' + measures[i].name + ' Children ' + j + '</label>' +
                                        '<input type="text" class="form-control measure-input" name="' + measures[i].code + '_children_' + j + '" readonly>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>';
                            $('.measures-container').append(htmlPlus);
                        }
                    }
                }
                else if (measures[i].code == 'stop_sale') {
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
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Unset' +
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
                            //$('input[name=' + code + ']').val('');
                            $('input[name="set-' + code + '"]').prop('checked', '');
                            $(this).val($(this).attr('data-measure-id'));
                            $('#select-stop-sale').attr('disabled', 'disabled');
                        }
                        else {
                            $(this).val('0');
                        }
                    });
                }
                else {
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
                                        '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Unset' +
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
                            //$('input[name=' + name + ']').val('');
                        }
                    });
                    $('input[name="unset-' + measures[i].code + '"]').change(function() {
                        var code = $(this).attr('data-unset');
                        if($(this).is(":checked")) {
                            $('input[name=' + code + ']').prop('readonly', true);
                            $('input[name=' + code + ']').rules('remove', 'required');
                            //$('input[name=' + code + ']').val('');
                            $('input[name="set-' + code + '"]').prop('checked', '');
                            $(this).val($(this).attr('data-measure-id'));
                        }
                        else {
                            $(this).val('0');
                        }
                    });
                }
            }

            $('input[name="set-cost"]').change(function() {
                if($(this).is(":checked")) {
                    $('input[name^="cost"]').each(function(i) {
                        $(this).rules('add', 'required');
                        var code = $(this).attr('name');
                        if ($('.use-adult[data-set="' + code + '"]').prop('checked') == false || code == 'cost')
                            $(this).prop('readonly', '');
                    });
                    $('.label_contentainer').show();
                    $('input[name="unset-cost"]').prop('checked', '');
                    $('input[name="import-cost"]').prop('checked', '');
                }
                else {
                    $('input[name^="cost"]').each(function(i) {
                        $(this).rules('remove', 'required');
                        $(this).prop('readonly', true);
                    });
                    $('.label_contentainer').hide();
                    $('input[name="unset-cost"]').prop('checked', '');
                    $('input[name="import-cost"]').prop('checked', '');
                }
            });

            $('input[name="unset-cost"]').change(function() {
                if($(this).is(":checked")) {
                    $('input[name^="cost"]').each(function(i) {
                        $(this).rules('remove', 'required');
                        $(this).prop('readonly', true);
                        //$(this).val('');
                        $('input[name="set-price"]').prop('checked', '');
                        $('input[name=set-cost]').prop('checked', '');
                        $('input[name="import-cost"]').prop('checked', '');
                    });
                    $('input[name^="price"]').each(function(i) {
                        $(this).rules('remove', 'required');
                        $(this).prop('readonly', true);
                        //$(this).val('');
                    });
                    $('.label_contentainer').hide();
                }
            });

            $('input[name="set-price"]').change(function() {
                if($(this).is(":checked")) {
                    $('input[name^="cost"]').each(function(i) {
                        $(this).rules('add', 'required');
                        //$(this).prop('readonly', '');
                    });
                    $('input[name^="price"]').each(function(i) {
                        $(this).rules('add', 'required');
                        $(this).prop('readonly', '');
                    });
                }
                else {
                    $('input[name^="price"]').each(function(i) {
                        $(this).rules('remove', 'required');
                        $(this).prop('readonly', true);
                    });
                }
            });

            formSetting.validate();
            //$('input[name="cost"]').rules('add', 'required');
            formSetting.validate().resetForm();
            formSetting[0].reset();
            //var room = $(this).parents('table').find('th:first').html();
            $('#modal-setting .room-name-header').html(room.code + ': ' + room.name);

            var date = $(this).attr('data-date');
            $('#modal-setting :input[name=setting-from]').datepicker("setDate" , new Date(moment(date, 'YYYY-MM-DD')));
            $('#modal-setting :input[name=setting-to]').datepicker("setDate" , new Date(moment(date, 'YYYY-MM-DD')));

            $('#modal-setting .measures-container :input').prop('readonly', true);
            $('#modal-setting .measures-container :input').prop('checked', '');

            var measure = $(this).parents('tr').find('td:first').attr('data-measure-code');
            if (measure.substr(0, 4) == 'cost') {
                $('#modal-setting :input[data-set=cost]').click();
            }
            else if (measure.substr(0, 5) == 'price') {
                $('#modal-setting :input[data-set=price]').click();
            }
            else {
                $('#modal-setting :input[data-set=' + measure + ']').click();
            }
            $("#modal-setting :input[name=contract-id]").val(contract.id);
            $("#modal-setting :input[name=market-id]").val($(this).attr('data-market-id'));

            var measures = contract.measures;
            if (room.max_children > 0 && room.max_children < 3) {
                var measuresTemp = measures;
                measures = [];
                for (var i = 0; i < measuresTemp.length; i ++) {
                    measures.push(measuresTemp[i]);
                    if (measuresTemp[i].code == 'cost') {
                        for (var j = 1; j <= room.max_children; j ++) {
                            var obj = {
                                id: 1000 + j,
                                name: 'Cost CH ' + j,
                                active: 1,
                                code: 'cost_children_' + j
                            }
                            measures.push(obj);
                        }
                    }
                    else if (measuresTemp[i].code == 'price') {
                        for (var j = 1; j <= room.max_children; j ++) {
                            var obj = {
                                id: 2000 + j,
                                name: 'Price CH ' + j,
                                active: 1,
                                code: 'price_children_' + j
                            }
                            measures.push(obj);
                        }
                    }
                }
            }

            for (var i = 0; i < measures.length; i++) {
                var date = $(this).attr('data-date');
                var measureId = measures[i].id;
                var item = $(this).parents('table').find('td[data-date="' + date + '"][data-measure-id="' + measureId + '"]');
                var value = item.attr('data');
                var code = item.attr('data-measure-code');
                if (code == 'cost_children_1' || code == 'cost_children_2' || code == 'cost_children_3') {
                    var type = item.attr('data-use-adult-type');
                    var rate = item.attr('data-use-adult-rate');
                    var bonus = '';
                    if (type == 1)
                        bonus = '(' + rate + '%)';
                    else
                        bonus = '($' + rate + ')';
                    if (type != ''){
                        $('.use-adult[data-set="' + code + '"]').prop('checked', true);
                        $('.label_' + code).html('From Adult ' + bonus);
                        $('[data-set="' + code + '"][data-type="rate"]').val(rate);
                        $('[data-set="' + code + '"][data-type="type"]').val(type);
                        $('input[name="' + code + '"]').prop('readonly', true);
                        $('input[name="' + code + '"]').rules('remove', 'required');
                    }
                }
                if (measures[i].code == 'stop_sale') {
                    $('#select-stop-sale').val(value).change();
                }
                else {
                    $('#modal-setting :input[name="' + measures[i].code + '"]').val(value);
                }
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
                toastr['warning']('There are not sets or unsets to submit.', "Warning");
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
                    //"data": formSetting.serialize(),
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

    $('.add-row-import').on('click', function(e) {
        e.preventDefault();
        var time = $.now();
        var range =
            '<div class="range range-optional" data="' + time + '">' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>From</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="import-from-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>To</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="import-to-' + time + '">' +
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
        $('.import-range-container').append(range);

        $('input[name="import-from-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var start = $(this).val();
            var end = $('input[name="import-to-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (start != 0 && start != '' && (end == 0 || end == '' || moment(endDate).isBefore(startDate))) {
                $('input[name="import-to-' + time + '"]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
            }
        });

        $('input[name="import-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var end = $(this).val();
            var start = $('input[name="import-from-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (end != 0 && end != '' && (start == 0 || start == '' || moment(endDate).isBefore(startDate))) {
                $('input[name="import-from-' + time + '"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
            }
        });

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');

        $('input[name="import-from-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name="import-from-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));
        $('input[name="import-to-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name="import-to-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));

        formImport.validate();
        $('input[name="import-from-' + time + '"]').rules('add', 'required');
        $('input[name="import-to-' + time + '"]').rules('add', 'required');

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
        var rooms = contract.room_types;
        //var name = $('#modal-change :input[name="change-room"]').select2('data')[0]['text'];
        //var name = $('#modal-change :input[name="change-room"]').select2().find(":selected").data("name");
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
        updateImportCost(rooms);
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

    $('#modal-use-adult [id=use-adult-rate_fee_value]').TouchSpin({
        min: -1000000000,
        max: 1000000000,
        stepinterval: 50,
        decimals: 2,
        maxboostedstep: 10000000,
        prefix: '$'
    });

    $('#modal-use-adult [id=use-adult-rate_percent_value]').TouchSpin({
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
            $('.add-value-container').show();

        }
        else {
            $('.add-value-container').hide();
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

    $('input[name="use-adult-type"]').change(function() {
        var type = $(this).val();
        if (type == 1) {
            $('input[name="use-adult-rate_fee_value"]').attr('disabled', 'disabled').val('');
            $('input[name="use-adult-rate_percent_value"]').removeAttr('disabled');
        }
        else {
            $('input[name="use-adult-rate_percent_value"]').attr('disabled', 'disabled').val('');
            $('input[name="use-adult-rate_fee_value"]').removeAttr('disabled');
        }
        $('input[name=use-adult-rate-type]').val(type);
    });

    $('.cancel-use-adult').on('click', function(e) {
        $('.use-adult-input-rate').each(function() {
            $(this).rules('remove', 'required');
        });
    });

    var formImport = $('#form-import');
    formImport.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            "import-from": {
                required: true,
                validDate: true
            },
            "import-to": {
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
            var contractId = $('#modal-setting :input[name="contract-id"]').val();
            var roomTypeId = $('#modal-setting :input[name="room-type-id"]').val();
            var marketId = $('#modal-setting :input[name="market-id"]').val();
            var formData = new FormData(formImport[0]);
            var ranges = [];
            $('#modal-import .range').each(function () {
                var obj = {
                    from : $(this).find('input[name^="import-from"]').val(),
                    to : $(this).find('input[name^="import-to"]').val()
                };
                ranges.push(obj);
            });
            formData.append('ranges', JSON.stringify(ranges));
            formData.append('contract-id', contractId);
            formData.append('room-type-id', roomTypeId);
            formData.append('market-id', marketId);
            $.ajax({
                "url": routeImportCostFromRoomtype,
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
