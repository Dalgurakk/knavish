$(document).ready(function () {
    var needUpdate = false;
    var formSearch = $('#search-accomodation');
    var contract = null;
    var operateMeasures = null;
    var searched = false;

    getContract();

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
        var start = $('#modal-setting :input[name="setting-from"]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
            $('#modal-setting :input[name="setting-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
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
        var start = $('#modal-import :input[name="import-from"]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
            $('#modal-import :input[name="import-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
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
            searched = false;
            contractId = value.id;
            getContract();
            fillContract(contract);
            var url = window.location.href;
            if (url.indexOf("?") > 0) {
                var updatedUri = url.substring(0, url.indexOf("?"));
                window.history.replaceState({}, document.title, updatedUri);
            }
        }
    });

    $('.btn-refresh').on('click', function () {
        getContract();
        searched = false;
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
            var boardType = $('#board-type').val();
            var roomTypes = [];
            $('.room-selected:checked').each(function () {
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
                    market: market,
                    boardType: boardType,
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
                            operateMeasures = response.operateMeasures;
                            operateTable(response.from, response.to, contract);

                            var offers = contract.offers;

                            $('.complement').each(function () {
                                var code = $(this).attr('data-measure-code');
                                var data = $(this).attr('data');
                                if (code == 'offer' && data != '') {
                                    var found = false;
                                    var offersInDay = JSON.parse("[" + data + "]");
                                    var table =
                                        '<table class="table table-striped table-complement" width="100%" cellspacing="0" style="margin-bottom: 0">' +
                                        '<thead>' +
                                        '<tr>' +
                                        '<th> # </th>' +
                                        '<th> Denomination </th>' +
                                        '<th> Type </th>' +
                                        '</thead>' +
                                        '<tbody>';
                                    for (var i = 0; i < offersInDay.length; i++) {
                                        for (var j = 0; j < offers.length; j++) {
                                            if (offersInDay[i] == offers[j].id) {
                                                found = true;
                                                table +=
                                                    '<tr><td>' + (i + 1) + '</td><td>' + offers[j].name + '</td><td>' + offers[j].offer_type.name + '</td></tr>';
                                                break;
                                            }
                                        }
                                    }
                                    table +=
                                        '</tbody>' +
                                        '</table>';
                                    if (found) {
                                        $(this).popover({
                                            trigger: 'hover',
                                            container: 'body',
                                            placement : 'top',
                                            title: 'Offers',
                                            content: table,
                                            delay: { "show": 500, "hide": 100 },
                                            html: true,
                                            template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title porlet-title-setting"></h3><div class="popover-content" style="padding: 5px;"></div></div>'
                                        });
                                    }
                                }
                            });
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

    function getContract() {
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
    }

    function loadOffers(offers) {
        contract.offers = offers;
        tableOffer.api().clear();
        for (var i = 0; i < offers.length; i++) {
            var options =
                '<div class="dt-actions">' +
                '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info-offer">' +
                '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>'+
                '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-edit" data-toggle="modal" href="#modal-edit-offer">' +
                '<i class="icon-pencil btn-action-icon"></i></a>' +
                '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-delete" href="javascript:;" data-popout="true" data-placement="left"' +
                'data-btn-ok-label="Yes" data-btn-ok-class="btn-sm btn-success"  data-btn-ok-icon-content="check" ' +
                'data-btn-cancel-label="No" data-btn-cancel-class="btn-sm btn-danger" data-btn-cancel-icon-content="close" data-title="Are you sure?" data-content="">' +
                '<i class="icon-trash btn-action-icon"></i></a>' +
                '</div>';
            var active = '<span><i class="fa fa-close dt-active dt-active-0"></i></span>';
            if (offers[i].active == 1)
                active = '<span><i class="fa fa-check dt-active dt-active-1"></i></span>';
            tableOffer.api().row.add([
                offers[i].id,
                offers[i].name,
                offers[i].offer_type.name,
                active,
                options,
                offers[i]
            ]).draw( false );
        }
        tableOffer.api().columns.adjust().draw();
    }

    function fillContract(c) {
        var roomTypes = c.room_types;
        var boardTypes = c.board_types;
        var measures = c.measures;
        var markets = c.markets;
        var offers = c.offers;
        var supplements = [];
        var restrictions = [];
        var contract = c;
        var chain = contract.hotel.hotel_chain != null ? contract.hotel.hotel_chain.name : '';
        var status = contract.active == 1 ? 'Enabled' : 'Disabled';

        $("#search-accomodation :input[name=hotel]").val(contract.hotel.name);
        $("#search-accomodation :input[name=hotel-chain]").val(chain);
        $("#search-accomodation :input[name=status]").val(status);
        $("#search-accomodation :input[name=period]").val(moment(contract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY') + ' - ' + moment(contract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
        $('.result-container').html('');
        $('.measures-list').html('');
        $('.btn-complements').addClass('disabled');
        $('.complement-link').each(function () {
            $(this).addClass('hide');
            var ref = $(this).children('a').attr('href');
            $(ref).addClass('hide');
        });

        $('#modal-complements .contract-name').html(contract.name);

        loadOffers(offers);

        var isFirstTab = true;
        $.each(measures, function (i, item) {
            if (measures[i].code == 'offer' || measures[i].code == 'supplement' || measures[i].code == 'restriction') {
                $('.complement-link[data="' + measures[i].code + '"]').removeClass('hide');
                $('.tab-pane[data="' + measures[i].code + '"]').removeClass('hide');
                if(isFirstTab) {
                    $('.complement-link[data="' + measures[i].code + '"]>a').click();
                    isFirstTab = false;
                }
            }
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

        if (offers.length > 0 || supplements.length > 0 || restrictions.length > 0) {
            $('.btn-complements').removeClass('disabled');
        }

        $('#market').empty();
        $.each(markets, function (i, item) {
            var option = '<option value="' + markets[i].id + '"> ' + markets[i].name + '</option>';
            $('#market').append(option);
        });

        $('#board-type').empty();
        $.each(boardTypes, function (i, item) {
            var option = '<option value="' + boardTypes[i].id + '"> ' + boardTypes[i].code + ': ' + boardTypes[i].name + '</option>';
            $('#board-type').append(option);
        });

        updateImport(markets);

        $('.room-types-list').html('');
        $('#modal-change :input[name="change-room"]').empty();
        $.each(roomTypes, function (i, item) {
            var roomType =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" class="room-selected" checked value="' + roomTypes[i].id + '"> ' + roomTypes[i].code + ': ' + roomTypes[i].name +
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

        $('input[name=from]').datepicker({
            format: "MM yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true,
            orientation: "bottom"
        });

        $('input[name=to]').datepicker({
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

        App.reloadToolTips();
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

    function operateTable(from, to, contract) {
        $('.item-setting').on('click', function() {
            if ($(this).hasClass('complement')) {
                var code = $(this).attr('data-measure-code');
                if (code == 'offer') {
                    var data = $(this).attr('data');
                    var offersInDay = JSON.parse("[" + $(this).attr('data') + "]");
                    if (offersInDay.length > 0) {
                        var offers = contract.offers;
                        $('#modal-offers .offers-container').html('');
                        var content = '';
                        for (var i = 0; i < offersInDay.length; i++) {
                            for (var j = 0; j < offers.length; j++) {
                                if (offersInDay[i] == offers[j].id) {
                                    var rooms = offers[j].rooms;
                                    var roomsStr = '';
                                    for (var r = 0; r < rooms.length; r++) {
                                        if (r == 0) {
                                            roomsStr += rooms[r].room_type.code + ': ' + rooms[r].room_type.name;
                                        }
                                        else {
                                            roomsStr += ', ' + rooms[r].room_type.code + ': ' + rooms[r].room_type.name;
                                        }
                                    }
                                    var boards = offers[j].boards;
                                    var boardsStr = '';
                                    for (var r = 0; r < boards.length; r++) {
                                        if (r == 0) {
                                            boardsStr += boards[r].board_type.code + ': ' + boards[r].board_type.name;
                                        }
                                        else {
                                            boardsStr += ', ' + boards[r].board_type.code + ': ' + boards[r].board_type.name;
                                        }
                                    }
                                    var ranges = offers[j].ranges;
                                    var rangesStr = '';
                                    for (var r = 0; r < ranges.length; r++) {
                                        var from = moment(ranges[r].from, 'YYYY-MM-DD').format('DD.MM.YYYY');
                                        var to = moment(ranges[r].to, 'YYYY-MM-DD').format('DD.MM.YYYY');
                                        if (r == 0) {
                                            if (from == to)
                                                rangesStr += from;
                                            else
                                                rangesStr += from + ' - ' + to;
                                        }
                                        else {
                                            if (from == to)
                                                rangesStr += ', ' + from;
                                            else
                                                rangesStr += ', ' + from + ' - ' + to;
                                        }
                                    }
                                    var details = '';
                                    var detailContent = '';
                                    var time = $.now();
                                    if (offers[j].offer_type.code == 'early_booking') {
                                        details =
                                        '<div class="actions">' +
                                            '<a href="javascript:;" class="btn btn-default btn-sm btn-offer-details" data="' + time + '" style="margin-right: 5px;">' +
                                            '<i class="fa fa-binoculars"></i> Details </a>' +
                                        '</div>';
                                        if (offers[j].discount != null) {
                                            detailContent +=
                                                '<div class="row static-info">' +
                                                    '<div class="col-md-3 name"> Discount: </div>';
                                            if (offers[j].discount_type == 1) {
                                                detailContent +=
                                                    '<div class="col-md-9 value"> ' + offers[j].discount + '%</div>' +
                                                '</div>';
                                            }
                                            else {
                                                detailContent +=
                                                    '<div class="col-md-9 value">$' + offers[j].discount + ' </div>' +
                                                '</div>';
                                            }
                                        }
                                        if (offers[j].booking_type != null) {
                                            var bookingDateStr = '';
                                            if (offers[j].booking_type == 1) {
                                                if (offers[j].booking_date_from == offers[j].booking_date_to)
                                                    bookingDateStr = moment(offers[j].booking_date_from, 'YYYY-MM-DD').format('DD.MM.YYYY');
                                                else {
                                                    bookingDateStr = moment(offers[j].booking_date_from, 'YYYY-MM-DD').format('DD.MM.YYYY') + ' - ' + moment(offers[j].booking_date_to, 'YYYY-MM-DD').format('DD.MM.YYYY');
                                                }
                                            }
                                            else if (offers[j].booking_type == 2) {
                                                if (offers[j].days_prior_from == offers[j].days_prior_to)
                                                    bookingDateStr = offers[j].days_prior_from + ' days prior to the check-in';
                                                else {
                                                    bookingDateStr = offers[j].days_prior_from + ' - ' + offers[j].days_prior_to + ' days prior to the check-in';
                                                }
                                            }
                                            detailContent +=
                                                '<div class="row static-info">' +
                                                    '<div class="col-md-3 name"> Booking Date: </div>' +
                                                    '<div class="col-md-9 value"> ' + bookingDateStr + ' </div>' +
                                                '</div>';
                                        }
                                        if (offers[j].payment_date != null) {
                                            detailContent +=
                                                '<div class="row static-info">' +
                                                    '<div class="col-md-3 name"> Payment Date: </div>' +
                                                    '<div class="col-md-9 value"> ' + moment(offers[j].payment_date, 'YYYY-MM-DD').format('DD.MM.YYYY') + ' </div>' +
                                                '</div>' +
                                                '<div class="row static-info">' +
                                                    '<div class="col-md-3 name"> Percentage Due: </div>' +
                                                    '<div class="col-md-9 value"> ' + offers[j].percentage_due + '%</div>' +
                                                '</div>';
                                        }
                                        if (offers[j].minimum_stay != null) {
                                            detailContent +=
                                                '<div class="row static-info">' +
                                                    '<div class="col-md-3 name"> Minimum Stay: </div>' +
                                                    '<div class="col-md-9 value"> ' + offers[j].minimum_stay + ' </div>' +
                                                '</div>';
                                        }
                                        var nonRefundable = offers[j].non_refundable == 1 ? 'Yes' : 'No';
                                        detailContent +=
                                                '<div class="row static-info">' +
                                                    '<div class="col-md-3 name"> Non Refundable: </div>' +
                                                    '<div class="col-md-9 value"> ' + nonRefundable + ' </div>' +
                                                '</div>';

                                    }
                                    content +=
                                        '<div class="col-md-12">' +
                                            '<div class="portlet box blue">' +
                                                '<div class="portlet-title">' +
                                                    '<div class="caption">' +
                                                        '<i class="fa fa-gift"></i>' + offers[j].name + '</div>' +
                                                    '<div class="tools">' +
                                                        '<a href="javascript:;" class="collapse"> </a></div>' + details +
                                                '</div>' +
                                                '<div class="portlet-body">' +
                                                    '<div class="row static-info">' +
                                                        '<div class="col-md-3 name"> Denomination: </div>' +
                                                        '<div class="col-md-9 value"> ' + offers[j].name + '</div>' +
                                                    '</div>' +
                                                    '<div class="row static-info">' +
                                                        '<div class="col-md-3 name"> Type: </div>' +
                                                        '<div class="col-md-9 value"> ' + offers[j].offer_type.name + ' </div>' +
                                                    '</div>' +
                                                    '<div class="row static-info">' +
                                                        '<div class="col-md-3 name"> Ranges: </div>' +
                                                        '<div class="col-md-9 value"> ' + rangesStr + ' </div>' +
                                                    '</div>' +
                                                    '<div class="row static-info">' +
                                                        '<div class="col-md-3 name"> Board Types: </div>' +
                                                        '<div class="col-md-9 value"> ' + boardsStr + ' </div>' +
                                                    '</div>' +
                                                    '<div class="row static-info">' +
                                                        '<div class="col-md-3 name"> Rooms: </div>' +
                                                        '<div class="col-md-9 value"> ' + roomsStr + ' </div>' +
                                                    '</div>' +
                                                    '<div class="offer-detail-container hidden" data="' + time + '">' +
                                                        detailContent +
                                                    '</div>' +
                                                '</div>' +
                                            '</div>' +
                                        '</div>';
                                    break;
                                }
                            }
                        }
                        $('#modal-offers .offers-container').append(content);
                        $('.btn-offer-details').on('click', function () {
                            var data = $(this).attr('data');
                            if ($('.offer-detail-container[data="' + data + '"]').hasClass('hidden')) {
                                $('.offer-detail-container[data="' + data + '"]').removeClass('hidden');
                            }
                            else {
                                $('.offer-detail-container[data="' + data + '"]').addClass('hidden');
                            }
                        });
                        $('#modal-offers').modal('show');
                    }
                    else {
                        return false;
                    }
                }
            }
            else {
                var roomTypes = contract.room_types;
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
                $('.measures-container').html('');
                for (var i = 0; i < measures.length; i++) {
                    if (measures[i].code != 'offer' && measures[i].code != 'supplement' && measures[i].code != 'restriction') {
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
                                '<option value="0">Open Sales</option>' +
                                '<option value="1">Stop Sales</option>' +
                                '<option value="2">On Request</option>' +
                                '</select>' +
                                '</div>' +
                                '</div>' +
                                '<div class="col-md-7 col-sm-7 col-xs-7">' +
                                '<div class="mt-checkbox-inline">' +
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                                '<input type="checkbox" class="set" value="" name="set-' + measures[i].code + '" data-set="' + measures[i].code + '" data-measure-id="' + measures[i].id + '" />' +
                                '<span></span>' +
                                '</label>' +
                                /*'<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-30"> Unset' +
                                    '<input type="checkbox" class="set" value="0" name="unset-' + measures[i].code + '" data-unset="' + measures[i].code + '" data-measure-id="' + measures[i].id + '"/>' +
                                    '<span></span>' +
                                '</label>' +*/
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
                            var name = measures[i].code == 'allotment' ? 'Allotment Base' : measures[i].name;
                            var html =
                                '<div class="row">' +
                                '<div class="col-md-12">' +
                                '<div class="col-md-5 col-sm-5 col-xs-5">' +
                                '<div class="form-group">' +
                                '<label>' + name + '</label>' +
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
                $('#modal-import .room-name-header').html(room.code + ': ' + room.name);

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
                else if (measure.substr(0, 9) == 'allotment') {
                    $('#modal-setting :input[data-set=allotment]').click();
                }
                else {
                    $('#modal-setting :input[data-set=' + measure + ']').click();
                }
                $("#modal-setting :input[name=contract-id]").val(contract.id);
                $("#modal-setting :input[name=market-id]").val($(this).attr('data-market-id'));
                $("#modal-setting :input[name=board-type-id]").val($('#board-type').val());

                var measures = operateMeasures;
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
                        $('#modal-setting :input[name="' + measures[i].code + '"]').val(value);
                    }
                    else if (measures[i].code == 'stop_sale') {
                        value = value == '' ? 0 : value;
                        $('#select-stop-sale').val(value).change();
                    }
                    else if (measures[i].code == 'allotment') {
                        value = $(this).parents('table').find('td[data-date="' + date + '"][data-measure-id="3002"]').attr('data');
                        $('#modal-setting :input[name="' + measures[i].code + '"]').val(value);
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
            }
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

    function countSelectedRecords(table) {
        //var selected = $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).size();
        //return selected;
        var selected = table.api().rows('.active').data();
        return selected.length;
    }

    /*function getSelectedRows(table) {
        var rows = [];
        $('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).each(function() {
            var data = table.api().row( $(this).parents('tr') ).data();
            rows.push(data[1]);
        });
        return rows;
    }*/

    function getSelectedRows(table) {
        var rows = [];
        var selected = table.api().rows('.active').data();
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
                //toastr['warning']('There are not sets or unsets to submit.', "Warning");
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

        $('#modal-setting :input[name="setting-from-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var start = $(this).val();
            var end = $('modal-setting :input[name="setting-to-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (start != 0 && start != '' && (end == 0 || end == '' || moment(endDate).isBefore(startDate))) {
                $('modal-setting :input[name="setting-to-' + time + '"]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
            }
        });

        $('#modal-setting :input[name="setting-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var end = $(this).val();
            var start = $('#modal-setting :input[name="setting-from-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (end != 0 && end != '' && (start == 0 || start == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-setting :input[name="setting-from-' + time + '"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
            }
        });

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');

        $('#modal-setting :input[name="setting-from-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-setting :input[name="setting-from-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));
        $('#modal-setting :input[name="setting-to-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-setting :input[name="setting-to-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));

        formSetting.validate();
        $('#modal-setting :input[name="setting-from-' + time + '"]').rules('add', 'required');
        $('#modal-setting :input[name="setting-to-' + time + '"]').rules('add', 'required');

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

        $('#modal-import :input[name="import-from-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var start = $(this).val();
            var end = $('#modal-import :input[name="import-to-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (start != 0 && start != '' && (end == 0 || end == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-import :input[name="import-to-' + time + '"]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
            }
        });

        $('#modal-import :input[name="import-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var end = $(this).val();
            var start = $('#modal-import :input[name="import-from-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (end != 0 && end != '' && (start == 0 || start == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-import :input[name="import-from-' + time + '"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
            }
        });

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');

        $('#modal-import :input[name="import-from-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-import :input[name="import-from-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));
        $('#modal-import :input[name="import-to-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-import :input[name="import-to-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));

        formImport.validate();
        $('#modal-import :input[name="import-from-' + time + '"]').rules('add', 'required');
        $('#modal-import :input[name="import-to-' + time + '"]').rules('add', 'required');

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

    $('.cancel-supplements').on('click', function(e) {
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

    $('#board-type').change(function() {
        if(searched) {
            $('.btn-search-submit').click();
        }
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
        $('#modal-import .room-name-header').html(room.code + ': ' + room.name);
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
        step: 0.01,
        decimals: 2,
        maxboostedstep: 10,
        prefix: '$'
    });

    $('#modal-import [id=rate_percent_value]').TouchSpin({
        min: -100,
        max: 100,
        step: 0.01,
        decimals: 2,
        boostat: 5,
        maxboostedstep: 10,
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
            var boardTypeId = $('#modal-setting :input[name="board-type-id"]').val();
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
            formData.append('board-type-id', boardTypeId);
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

    $('#modal-add-offer :input[name=valid-from]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var start = $(this).val();
        var end = $('#modal-add-offer :input[name=valid-to]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (end == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
            $('#modal-add-offer :input[name=valid-to]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
        }
    });

    $('#modal-add-offer :input[name=valid-to]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var end = $(this).val();
        var start = $('#modal-add-offer :input[name="valid-from"]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
            $('#modal-add-offer :input[name="valid-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
        }
    });

    $('#modal-edit-offer :input[name=valid-from]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var start = $(this).val();
        var end = $('#modal-edit-offer :input[name=valid-to]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (end == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
            $('#modal-edit-offer :input[name=valid-to]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
        }
    });

    $('#modal-edit-offer :input[name=valid-to]').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
    }).on('changeDate', function(e) {
        var end = $(this).val();
        var start = $('#modal-edit-offer :input[name="valid-from"]').val();
        var startDate = moment(start, 'DD.MM.YYYY');
        var endDate = moment(end, 'DD.MM.YYYY');
        if (start == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
            $('#modal-edit-offer :input[name="valid-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
        }
    });

    jQuery.validator.addMethod("requireFiled", function(value, element, param) {
        if ($(param).val() != '' && value == '')
            return false;
        else
            return true;
    }, "This field is required.");

    $('#modal-add-offer :input[name=offer-type]').change(function () {
        $('#modal-add-offer .offer-input-container').html('');
        var optionSelected = $('#modal-add-offer :input[name=offer-type] option:selected').attr('data-code');
        if (optionSelected == 'early_booking') {
            var html =
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Non Refundable' +
                '<input type="checkbox" value="1" name="non-refundable"/>' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Apply with other offers' +
                '<input type="checkbox" value="1" name="apply-with-other-offers"/>' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Minimum Stay</label>' +
                '<input type="text" class="form-control" placeholder="Minimum Stay" name="minimum-stay">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Type</label>' +
                '<select class="form-control" name="booking-type">' +
                '<option value="1">Date range</option>' +
                '<option value="2">Days prior to the check-in</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row booking-date-range">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Date From</label>' +
                '<input type="text" class="form-control" placeholder="Booking Date From" name="booking-date-from">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Date To</label>' +
                '<input type="text" class="form-control" placeholder="Booking Date To" name="booking-date-to">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row booking-days-prior">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Days From</label>' +
                '<input type="text" class="form-control" placeholder="Days From" name="days-from">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Days To</label>' +
                '<input type="text" class="form-control" placeholder="Days To" name="days-to">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Payment Date</label>' +
                '<input type="text" class="form-control" placeholder="Payment Date" name="payment-date">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Percentage Due</label>' +
                '<input type="text" class="form-control" placeholder="Percentage Due" name="percentage-due">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Discount</label>' +
                '<input type="text" class="form-control" placeholder="Discount" name="discount">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Discount Type</label>' +
                '<select class="form-control" name="discount-type">' +
                '<option value="1">Percent</option>' +
                '<option value="2">Fee</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>'
            ;
            $('#modal-add-offer .offer-input-container').append(html);

            $('#modal-add-offer :input[name=booking-date-from]').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'dd.mm.yyyy',
                orientation: "bottom"
            }).on('changeDate', function(e) {
                var start = $(this).val();
                var end = $('#modal-add-offer :input[name=booking-date-to]').val();
                var startDate = moment(start, 'DD.MM.YYYY');
                var endDate = moment(end, 'DD.MM.YYYY');
                if (end == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
                    $('#modal-add-offer :input[name=booking-date-to]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
                }
            });

            $('#modal-add-offer :input[name=booking-date-to]').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'dd.mm.yyyy',
                orientation: "bottom"
            }).on('changeDate', function(e) {
                var end = $(this).val();
                var start = $('#modal-add-offer :input[name="booking-date-from"]').val();
                var startDate = moment(start, 'DD.MM.YYYY');
                var endDate = moment(end, 'DD.MM.YYYY');
                if (start == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
                    $('#modal-add-offer :input[name="booking-date-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
                }
            });

            $('#modal-add-offer :input[name=payment-date]').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'dd.mm.yyyy',
                orientation: "bottom"
            }).on('changeDate', function(e) {
                //
            });

            $('#modal-add-offer :input[name=payment-date]').rules('add', 'validDate');
            $('#modal-add-offer :input[name=discount]').rules('add', 'required');
            $('#modal-add-offer :input[name=discount-type]').rules('add', 'required');
            $('#modal-add-offer :input[name=booking-type]').rules('add', 'required');

            $('#modal-add-offer :input[name=percentage-due]').rules("add", {
                requireFiled: '#modal-add-offer :input[name=payment-date]'
            });

            $('#modal-add-offer :input[name=payment-date]').rules("add", {
                requireFiled: '#modal-add-offer :input[name=percentage-due]'
            });

            $('#modal-add-offer :input[name=booking-type]').change(function () {
                $('#modal-add-offer :input[name=days-from]').each(function() {
                    $(this).rules('remove');
                });
                $('#modal-add-offer :input[name=days-to]').each(function() {
                    $(this).rules('remove');
                });
                $('#modal-add-offer :input[name=booking-date-from]').each(function() {
                    $(this).rules('remove');
                });
                $('#modal-add-offer :input[name=booking-date-to]').each(function() {
                    $(this).rules('remove');
                });

                $('#modal-add-offer :input[name=booking-date-from]').parent().removeClass('has-error');
                $('#modal-add-offer :input[name=booking-date-to]').parent().removeClass('has-error');
                $('#modal-add-offer :input[name=days-from]').parent().removeClass('has-error');
                $('#modal-add-offer :input[name=days-to]').parent().removeClass('has-error');

                $('#modal-add-offer :input[name=booking-date-from]').next('.help-block-error').hide();
                $('#modal-add-offer :input[name=booking-date-to]').next('.help-block-error').hide();
                $('#modal-add-offer :input[name=days-from]').next('.help-block-error').hide();
                $('#modal-add-offer :input[name=days-to]').next('.help-block-error').hide();

                $('#modal-add-offer :input[name=days-from]').val('');
                $('#modal-add-offer :input[name=days-to]').val('');
                $('#modal-add-offer :input[name=booking-date-from]').val('');
                $('#modal-add-offer :input[name=booking-date-to]').val('');

                var value = $(this).val();
                if (value == 1) {
                    $('#modal-add-offer :input[name=booking-date-from]').rules('add', {
                        required: true,
                        validDate: true
                    });
                    $('#modal-add-offer :input[name=booking-date-to]').rules('add', {
                        required: true,
                        validDate: true
                    });
                    $('#modal-add-offer .booking-date-range ').show();
                    $('#modal-add-offer .booking-days-prior ').hide();
                }
                else {
                    $('#modal-add-offer :input[name=days-to]').rules("add", {
                        required: true,
                        notLessThan: '#modal-add-offer :input[name=days-from]',
                        messages : {
                            notLessThan : 'Please enter a value greater than or equal to Days To field.'
                        }
                    });
                    $('#modal-add-offer :input[name=days-from]').rules('add', 'required');
                    $('#modal-add-offer .booking-date-range ').hide();
                    $('#modal-add-offer .booking-days-prior ').show();
                }
            });
            $('#modal-add-offer :input[name=booking-type]').val(1).change();
        }
    });

    $('#modal-edit-offer :input[name=offer-type]').change(function () {
        $('#modal-edit-offer .offer-input-container').html('');
        var optionSelected = $('#modal-edit-offer :input[name=offer-type] option:selected').attr('data-code');
        if (optionSelected == 'early_booking') {
            var html =
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Non Refundable' +
                '<input type="checkbox" value="1" name="non-refundable"/>' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Apply with other offers' +
                '<input type="checkbox" value="1" name="apply-with-other-offers"/>' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Minimum Stay</label>' +
                '<input type="text" class="form-control" placeholder="Minimum Stay" name="minimum-stay">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Type</label>' +
                '<select class="form-control" name="booking-type">' +
                '<option value="1">Date range</option>' +
                '<option value="2">Days prior to the check-in</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row booking-date-range">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Date From</label>' +
                '<input type="text" class="form-control" placeholder="Booking Date From" name="booking-date-from">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Date To</label>' +
                '<input type="text" class="form-control" placeholder="Booking Date To" name="booking-date-to">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row booking-days-prior">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Days From</label>' +
                '<input type="text" class="form-control" placeholder="Days From" name="days-from">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Days To</label>' +
                '<input type="text" class="form-control" placeholder="Days To" name="days-to">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Payment Date</label>' +
                '<input type="text" class="form-control" placeholder="Payment Date" name="payment-date">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Percentage Due</label>' +
                '<input type="text" class="form-control" placeholder="Percentage Due" name="percentage-due">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Discount</label>' +
                '<input type="text" class="form-control" placeholder="Discount" name="discount">' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Discount Type</label>' +
                '<select class="form-control" name="discount-type">' +
                '<option value="1">Percent</option>' +
                '<option value="2">Fee</option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '</div>'
            ;
            $('#modal-edit-offer .offer-input-container').append(html);

            $('#modal-edit-offer :input[name=booking-date-from]').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'dd.mm.yyyy',
                orientation: "bottom"
            }).on('changeDate', function(e) {
                var start = $(this).val();
                var end = $('#modal-edit-offer :input[name=booking-date-to]').val();
                var startDate = moment(start, 'DD.MM.YYYY');
                var endDate = moment(end, 'DD.MM.YYYY');
                if (end == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
                    $('#modal-edit-offer :input[name=booking-date-to]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
                }
            });

            $('#modal-edit-offer :input[name=booking-date-to]').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'dd.mm.yyyy',
                orientation: "bottom"
            }).on('changeDate', function(e) {
                var end = $(this).val();
                var start = $('#modal-edit-offer :input[name="booking-date-from"]').val();
                var startDate = moment(start, 'DD.MM.YYYY');
                var endDate = moment(end, 'DD.MM.YYYY');
                if (start == '' || (start != 0 && start != '' && moment(endDate).isBefore(startDate))){
                    $('#modal-edit-offer :input[name="booking-date-from"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
                }
            });

            $('#modal-edit-offer :input[name=payment-date]').datepicker({
                rtl: App.isRTL(),
                orientation: "left",
                autoclose: true,
                format: 'dd.mm.yyyy',
                orientation: "bottom"
            });

            $('#modal-edit-offer :input[name=payment-date]').rules('add', 'validDate');
            $('#modal-edit-offer :input[name=discount]').rules('add', 'required');
            $('#modal-edit-offer :input[name=discount-type]').rules('add', 'required');
            $('#modal-edit-offer :input[name=booking-type]').rules('add', 'required');

            $('#modal-edit-offer :input[name=percentage-due]').rules("add", {
                requireFiled: '#modal-edit-offer :input[name=payment-date]'
            });

            $('#modal-edit-offer :input[name=payment-date]').rules("add", {
                requireFiled: '#modal-edit-offer :input[name=percentage-due]'
            });

            $('#modal-edit-offer :input[name=booking-type]').change(function () {
                $('#modal-edit-offer :input[name=days-from]').each(function() {
                    $(this).rules('remove');
                });
                $('#modal-edit-offer :input[name=days-to]').each(function() {
                    $(this).rules('remove');
                });
                $('#modal-edit-offer :input[name=booking-date-from]').each(function() {
                    $(this).rules('remove');
                });
                $('#modal-edit-offer :input[name=booking-date-to]').each(function() {
                    $(this).rules('remove');
                });

                $('#modal-edit-offer :input[name=booking-date-from]').parent().removeClass('has-error');
                $('#modal-edit-offer :input[name=booking-date-to]').parent().removeClass('has-error');
                $('#modal-edit-offer :input[name=days-from]').parent().removeClass('has-error');
                $('#modal-edit-offer :input[name=days-to]').parent().removeClass('has-error');

                $('#modal-edit-offer :input[name=booking-date-from]').next('.help-block-error').hide();
                $('#modal-edit-offer :input[name=booking-date-to]').next('.help-block-error').hide();
                $('#modal-edit-offer :input[name=days-from]').next('.help-block-error').hide();
                $('#modal-edit-offer :input[name=days-to]').next('.help-block-error').hide();

                $('#modal-edit-offer :input[name=days-from]').val('');
                $('#modal-edit-offer :input[name=days-to]').val('');
                $('#modal-edit-offer :input[name=booking-date-from]').val('');
                $('#modal-edit-offer :input[name=booking-date-to]').val('');

                var value = $(this).val();
                if (value == 1) {
                    $('#modal-edit-offer :input[name=booking-date-from]').rules('add', {
                        required: true,
                        validDate: true
                    });
                    $('#modal-edit-offer :input[name=booking-date-to]').rules('add', {
                        required: true,
                        validDate: true
                    });
                    $('#modal-edit-offer .booking-date-range ').show();
                    $('#modal-edit-offer .booking-days-prior ').hide();
                }
                else {
                    $('#modal-edit-offer :input[name=days-to]').rules("add", {
                        required: true,
                        notLessThan: '#modal-edit-offer :input[name=days-from]',
                        messages : {
                            notLessThan : 'Please enter a value greater than or equal to Days To field.'
                        }
                    });
                    $('#modal-edit-offer :input[name=days-from]').rules('add', 'required');
                    $('#modal-edit-offer .booking-date-range ').hide();
                    $('#modal-edit-offer .booking-days-prior ').show();
                }
            });
            //$('#modal-edit-offer :input[name=booking-type]').val(1).change();
        }
    });

    var formAddOffer = $('#form-add-offer');
    formAddOffer.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            "valid-from" : {
                required: true,
                validDate: true
            },
            "valid-to" : {
                required: true,
                validDate: true
            },
            "count-offer-room-type": {
                greaterThanZero: true
            },
            "count-offer-board-type": {
                greaterThanZero: true
            },
            "name": {
                required: true
            },
            "offer-type": {
                required: true
            },
            "priority": {
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
            }else if (element.hasClass("add-offer")) {
                error.insertAfter(element.next());
            }else if (element.hasClass("hotel-type")) {
                error.insertAfter(element.next().next());
                $('#modal-add-offer :input[name=search-code]').css('border', '1px solid #c2cad8');
                $('#modal-add-offer :input[name=search-name]').css('border', '1px solid #c2cad8');
            }else if (element.hasClass("select-hotel")) {
                error.insertAfter(element.next());
            }else if (element.hasClass('market-rate-price-type')){
                error.appendTo('.market-rate-container-error');
                $('.market-rate-container-error > span').css('color', '#e73d4a');
            }else {
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
            //alert('send');
            var option = $(form).find("button[type=submit]:focus").attr('data');
            var formData = new FormData(formAddOffer[0]);
            var contractId = contract.id;
            var ranges = [];
            $('#modal-add-offer .range').each(function () {
                var obj = {
                    from : $(this).find('input[name^="valid-from"]').val(),
                    to : $(this).find('input[name^="valid-to"]').val()
                };
                ranges.push(obj);
            });
            var rooms = getSelectedRows(tableOfferRoomType);
            var boards = getSelectedRows(tableOfferBoardType);
            formData.append('room-types', JSON.stringify(rooms));
            formData.append('board-types', JSON.stringify(boards));
            formData.append('ranges', JSON.stringify(ranges));
            formData.append('contractId', contractId);

            $.ajax({
                "url": routeSaveOffer,
                "type": "POST",
                "data": formData,
                "contentType": false,
                "processData": false,
                "beforeSend": function() {
                    App.showMask(true, formAddOffer);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formAddOffer);
                    if (xhr.status == '419') {
                        location.reload(true);
                    }
                    else if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            var offers =response.data;
                            loadOffers(offers);
                            toastr['success'](response.message, "Success");
                            if (searched) {
                                needUpdate = true;
                            }
                            if (option == 'accept') {
                                $(form).find("button.cancel-form-offer").click();
                            }
                            resetAddOffer();
                        }
                        else {
                            toastr['error'](response.message, "Error");
                        }
                    }
                }
            });
        }
    });

    var formEditOffer = $('#form-edit-offer');
    formEditOffer.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            "valid-from" : {
                required: true,
                validDate: true
            },
            "valid-to" : {
                required: true,
                validDate: true
            },
            "count-offer-room-type": {
                greaterThanZero: true
            },
            "count-offer-board-type": {
                greaterThanZero: true
            },
            "name": {
                required: true
            },
            "offer-type": {
                required: true
            },
            "priority": {
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
            }else if (element.hasClass("hotel-type")) {
                error.insertAfter(element.next().next());
                $('#modal-edit-offer :input[name=search-code]').css('border', '1px solid #c2cad8');
                $('#modal-edit-offer :input[name=search-name]').css('border', '1px solid #c2cad8');
            }else if (element.hasClass("edit-offer")) {
                error.insertAfter(element.next());
            }else if (element.hasClass("select-hotel")) {
                error.insertAfter(element.next());
            }else if (element.hasClass('market-rate-price-type')){
                error.appendTo('.market-rate-container-error');
                $('.market-rate-container-error > span').css('color', '#e73d4a');
            }else {
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
            var formData = new FormData(formEditOffer[0]);
            var id = $('#modal-edit-offer :input[name=id]').val();
            var ranges = [];
            $('#modal-edit-offer .range').each(function () {
                var obj = {
                    from : $(this).find('input[name^="valid-from"]').val(),
                    to : $(this).find('input[name^="valid-to"]').val()
                };
                ranges.push(obj);
            });
            var rooms = getSelectedRows(tableEditOfferRoomType);
            var boards = getSelectedRows(tableEditOfferBoardType);
            formData.append('room-types', JSON.stringify(rooms));
            formData.append('board-types', JSON.stringify(boards));
            formData.append('ranges', JSON.stringify(ranges));
            formData.append('id', id);

            $.ajax({
                "url": routeUpdateOffer,
                "type": "POST",
                "data": formData,
                "contentType": false,
                "processData": false,
                "beforeSend": function() {
                    App.showMask(true, formEditOffer);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formEditOffer);
                    if (xhr.status == '419') {
                        location.reload(true);
                    }
                    else if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            var offers = response.data;
                            loadOffers(offers);
                            toastr['success'](response.message, "Success");
                            if (searched) {
                                needUpdate = true;
                            }
                            if (option == 'accept') {
                                $(form).find("button.cancel-form-offer").click();
                                formEditOffer[0].reset();
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

    $('#modal-add-offer .add-row-offer').on('click', function(e) {
        e.preventDefault();
        var time = $.now();
        var range =
            '<div class="range range-optional" data="' + time + '">' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>From</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="valid-from-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>To</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="valid-to-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2 col-sm-2 col-xs-2">' +
            '<div class="form-group">' +
            '<a class="btn red btn-outline delete-row delete-row-offer" href="#" data="' + time + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>';
        $('#modal-add-offer .range-container').append(range);

        $('#modal-add-offer :input[name="valid-from-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var start = $(this).val();
            var end = $('#modal-add-offer :input[name="valid-to-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (start != 0 && start != '' && (end == 0 || end == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-add-offer :input[name="valid-to-' + time + '"]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
            }
        });

        $('#modal-add-offer :input[name="valid-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var end = $(this).val();
            var start = $('#modal-add-offer :input[name="valid-from-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (end != 0 && end != '' && (start == 0 || start == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-add-offer :input[name="valid-from-' + time + '"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
            }
        });

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');

        $('#modal-add-offer :input[name="valid-from-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-add-offer :input[name="valid-from-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));
        $('#modal-add-offer :input[name="valid-to-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-add-offer :input[name="valid-to-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));

        formAddOffer.validate();
        $('#modal-add-offer :input[name="valid-from-' + time + '"]').rules('add', 'required');
        $('#modal-add-offer :input[name="valid-to-' + time + '"]').rules('add', 'required');

        $('#modal-add-offer .delete-row-offer[data="' + time + '"]').on('click', function(e) {
            $('#modal-add-offer .range[data="' + time + '"]').remove();
            e.preventDefault();
        });
    });

    $('#modal-edit-offer .add-row-offer').on('click', function(e) {
        e.preventDefault();
        var time = $.now();
        var range =
            '<div class="range range-optional" data="' + time + '">' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>From</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="valid-from-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-5 col-sm-5 col-xs-5">' +
            '<div class="form-group">' +
            '<label>To</label>' +
            '<div class="input-icon left">' +
            '<i class="fa fa-calendar"></i>' +
            '<input type="text" class="form-control date-picker" name="valid-to-' + time + '">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-2 col-sm-2 col-xs-2">' +
            '<div class="form-group">' +
            '<a class="btn red btn-outline delete-row delete-row-offer" href="#" data="' + time + '">' +
            '<i class="fa fa-trash"></i>' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>';
        $('#modal-edit-offer .range-container').append(range);

        $('#modal-edit-offer :input[name="valid-from-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var start = $(this).val();
            var end = $('#modal-edit-offer :input[name="valid-to-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (start != 0 && start != '' && (end == 0 || end == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-edit-offer :input[name="valid-to-' + time + '"]').datepicker( "setDate" , new Date(moment(start, 'DD.MM.YYYY')));
            }
        });

        $('#modal-edit-offer :input[name="valid-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        }).on('changeDate', function(e) {
            var end = $(this).val();
            var start = $('#modal-edit-offer :input[name="valid-from-' + time + '"]').val();
            var startDate = moment(start, 'DD.MM.YYYY');
            var endDate = moment(end, 'DD.MM.YYYY');
            if (end != 0 && end != '' && (start == 0 || start == '' || moment(endDate).isBefore(startDate))) {
                $('#modal-edit-offer :input[name="valid-from-' + time + '"]').datepicker( "setDate" , new Date(moment(end, 'DD.MM.YYYY')));
            }
        });

        var startDate = moment(contract.valid_from, 'YYYY-MM-DD');
        var endDate = moment(contract.valid_to, 'YYYY-MM-DD');

        $('#modal-edit-offer :input[name="valid-from-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-edit-offer :input[name="valid-from-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));
        $('#modal-edit-offer :input[name="valid-to-' + time + '"]').datepicker( "setStartDate" , new Date(startDate));
        $('#modal-edit-offer :input[name="valid-to-' + time + '"]').datepicker( "setEndDate" , new Date(endDate));

        formEditOffer.validate();
        $('#modal-edit-offer :input[name="valid-from-' + time + '"]').rules('add', 'required');
        $('#modal-edit-offer :input[name="valid-to-' + time + '"]').rules('add', 'required');

        $('#modal-edit-offer .delete-row-offer[data="' + time + '"]').on('click', function(e) {
            $('#modal-edit-offer .range[data="' + time + '"]').remove();
            e.preventDefault();
        });
    });

    $.fn.dataTable.ext.errMode = 'none';
    var tableOffer = $('#table-offer').dataTable({
        "sDom": "tip",
        "autoWidth": false,
        "columnDefs": [
            { 'visible': false, 'targets': [0], name: 'id' },
            { 'targets': [1], name: 'id', "width": "40%" },
            { targets: [3], className: "dt-center nobreakline", orderable: false, name: 'name' },
            { targets: [4], className: "dt-center nobreakline", orderable: false, name: 'type' },
            { 'visible': false, 'targets': [5], name: 'object' }
        ],
        "order": [[ 1, "asc" ]],
        "lengthMenu": [[-1], ["All"]],
        "pageLength": 10
    });

    $('#table-offer tbody').on( 'click', '.dt-edit', function (e) {
        $('#modal-edit-offer .delete-row-offer').each(function () {
            $(this).click();
        });
        var data = tableOffer.api().row( $(this).parents('tr') ).data();
        var offer = data['5'];
        var roomTypes = contract.room_types;
        var boardTypes = contract.board_types;
        var rooms = offer.rooms;
        var boards = offer.boards;
        var ranges = offer.ranges;
        var offerType = offer.offer_type;

        $('#modal-edit-offer .expand').each(function () {
            $(this).click();
        });


        for (var i = 0; i < ranges.length; i++) {
            if (i == 0) {
                $('#modal-edit-offer :input[name=valid-from]').datepicker("setDate" , new Date(moment(ranges[i].from, 'YYYY-MM-DD')));
                $('#modal-edit-offer :input[name=valid-to]').datepicker("setDate" , new Date(moment(ranges[i].to, 'YYYY-MM-DD')));
            }
            else {
                $('#modal-edit-offer .add-row-offer').click();
                $('#modal-edit-offer :input[name^=valid-from-]').last().datepicker("setDate" , new Date(moment(ranges[i].from, 'YYYY-MM-DD')));
                $('#modal-edit-offer :input[name^=valid-to-]').last().datepicker("setDate" , new Date(moment(ranges[i].to, 'YYYY-MM-DD')));
            }
        }

        tableEditOfferRoomType.api().clear();
        for (var i = 0; i < roomTypes.length; i++) {
            var selected = false;
            for (var j = 0; j < rooms.length; j++) {
                if (roomTypes[i].id == rooms[j].hotel_room_type_id) {
                    selected = true;
                    break;
                }
            }
            if (selected) {
                tableEditOfferRoomType.api().row.add([
                    '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> ' +
                    '<input type="checkbox" class="checkboxes" value="1" checked />' +
                    '<span></span>' +
                    '</label>',
                    roomTypes[i].id,
                    roomTypes[i].code,
                    roomTypes[i].name
                ]).draw().nodes().to$().addClass("active");
            }
            else {
                tableEditOfferRoomType.api().row.add([
                    '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> ' +
                    '<input type="checkbox" class="checkboxes" value="1"/>' +
                    '<span></span>' +
                    '</label>',
                    roomTypes[i].id,
                    roomTypes[i].code,
                    roomTypes[i].name
                ]).draw();
            }
        }
        $('#modal-edit-offer :input[name=count-offer-room-type]').val(rooms.length);
        tableEditOfferRoomType.api().columns.adjust().draw();

        tableEditOfferBoardType.api().clear();
        for (var i = 0; i < boardTypes.length; i++) {
            var selected = false;
            for (var j = 0; j < boards.length; j++) {
                if (boardTypes[i].id == boards[j].hotel_board_type_id) {
                    selected = true;
                    break;
                }
            }
            if (selected) {
                tableEditOfferBoardType.api().row.add([
                    '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> ' +
                    '<input type="checkbox" class="checkboxes" value="1" checked />' +
                    '<span></span>' +
                    '</label>',
                    boardTypes[i].id,
                    boardTypes[i].code,
                    boardTypes[i].name
                ]).draw().nodes().to$().addClass("active");
            }
            else {
                tableEditOfferBoardType.api().row.add([
                    '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"> ' +
                    '<input type="checkbox" class="checkboxes" value="1"/>' +
                    '<span></span>' +
                    '</label>',
                    boardTypes[i].id,
                    boardTypes[i].code,
                    boardTypes[i].name
                ]).draw();
            }
        }
        $('#modal-edit-offer :input[name=count-offer-board-type]').val(boards.length);
        tableEditOfferBoardType.api().columns.adjust().draw();

        $('#modal-edit-offer :input[name=id]').val(offer.id);
        $('#modal-edit-offer :input[name=name]').val(offer.name);
        $('#modal-edit-offer :input[name=priority]').val(offer.priority);
        if (offer.active == 1) {
            $('#modal-edit-offer :input[name=active]').prop('checked', 'checked');
            $('#modal-edit-offer :input[name=active]').val(1);
        }
        else {
            $('#modal-edit-offer :input[name=active]').prop('checked', '');
            $('#modal-edit-offer :input[name=active]').val(1);
        }
        $('#modal-edit-offer :input[name=offer-type]').val(offerType.id).change();
        var optionSelected = $('#modal-edit-offer :input[name=offer-type] option:selected').attr('data-code');

        if (optionSelected == 'early_booking') {
            if (offer.apply_with_other_offers == 1) {
                $('#modal-edit-offer :input[name="apply-with-other-offers"]').prop('checked', 'checked');
                $('#modal-edit-offer :input[name="apply-with-other-offers"]').val(1);
            }
            else {
                $('#modal-edit-offer :input[name="apply-with-other-offers"]').prop('checked', '');
                $('#modal-edit-offer :input[name="apply-with-other-offers"]').val(1);
            }
            if (offer.non_refundable == 1) {
                $('#modal-edit-offer :input[name="non-refundable"]').prop('checked', 'checked');
                $('#modal-edit-offer :input[name="non-refundable"]').val(1);
            }
            else {
                $('#modal-edit-offer :input[name="non-refundable"]').prop('checked', '');
                $('#modal-edit-offer :input[name="non-refundable"]').val(1);
            }

            $('#modal-edit-offer :input[name=booking-type]').val(offer.booking_type).change();
            $('#modal-edit-offer :input[name=days-from]').val(offer.days_prior_from);
            $('#modal-edit-offer :input[name=days-to]').val(offer.days_prior_to);
            $('#modal-edit-offer :input[name=booking-date-from]').datepicker("update" , new Date(moment(offer.booking_date_from, 'YYYY-MM-DD')));
            $('#modal-edit-offer :input[name=booking-date-to]').datepicker("update" , new Date(moment(offer.booking_date_to, 'YYYY-MM-DD')));
            $('#modal-edit-offer :input[name=payment-date]').datepicker("setDate" , new Date(moment(offer.payment_date, 'YYYY-MM-DD')));
            $('#modal-edit-offer :input[name=percentage-due]').val(offer.percentage_due);
            $('#modal-edit-offer :input[name=discount]').val(offer.discount);
            $('#modal-edit-offer :input[name=discount-type]').val(offer.discount_type);
            $('#modal-edit-offer :input[name=minimum-stay]').val(offer.minimum_stay);
        }
    });

    $('#table-offer tbody').on( 'click', '.dt-view', function (e) {
        var data = tableOffer.api().row( $(this).parents('tr') ).data();
        var offer = data['5'];
        var rooms = offer.rooms;
        var boards = offer.boards;
        var ranges = offer.ranges;
        var offerType = offer.offer_type;

        $('#modal-info-offer .expand').each(function () {
            $(this).click();
        });

        $('#modal-info-offer .range-container').html('');
        for (var i = 0; i < ranges.length; i++) {
            var html =
                '<div class="range">' +
                '<div class="col-md-6 col-sm-6 col-xs-6">' +
                '<div class="form-group">' +
                '<label>From</label>' +
                '<div class="input-icon left">' +
                '<i class="fa fa-calendar"></i>' +
                '<input type="text" class="form-control date-picker" name="valid-from" value="' + moment(ranges[i].from, 'YYYY-MM-DD').format('DD.MM.YYYY') + '" readonly>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6 col-sm-6 col-xs-6">' +
                '<div class="form-group">' +
                '<label>To</label>' +
                '<div class="input-icon left">' +
                '<i class="fa fa-calendar"></i>' +
                '<input type="text" class="form-control date-picker" name="valid-to" value="' + moment(ranges[i].to, 'YYYY-MM-DD').format('DD.MM.YYYY') + '" readonly>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
            $('#modal-info-offer .range-container').append(html);
        }

        tableInfoOfferRoomType.api().clear();
        for (var i = 0; i < rooms.length; i++) {
            tableInfoOfferRoomType.api().row.add([
                rooms[i].room_type.code,
                rooms[i].room_type.name
            ]).draw( false );
        }
        tableInfoOfferRoomType.api().columns.adjust().draw();

        tableInfoOfferBoardType.api().clear();
        for (var i = 0; i < boards.length; i++) {
            tableInfoOfferBoardType.api().row.add([
                boards[i].board_type.code,
                boards[i].board_type.name
            ]).draw( false );
        }
        tableInfoOfferBoardType.api().columns.adjust().draw();

        $('#modal-info-offer :input[name=name]').val(offer.name);
        $('#modal-info-offer :input[name=priority]').val(offer.priority);
        if (offer.active == 1) {
            $('#modal-info-offer :input[name=active]').prop('checked', 'checked');
            $('#modal-info-offer :input[name=active]').val(1);
        }
        else {
            $('#modal-info-offer :input[name=active]').prop('checked', '');
            $('#modal-info-offer :input[name=active]').val(0);
        }
        $('#modal-info-offer :input[name=offer-type]').val(offerType.name);

        $('#modal-info-offer .offer-input-container').html('');
        if (offerType.code == 'early_booking') {
            var bookingDateFrom = '';
            var bookingDateTo = '';
            var daysPriorFrom = '';
            var daysPriorTo = '';
            var rowBookingTypeData = '';
            var paymentDate = (offer.payment_date != '' && offer.payment_date != null) ? moment(offer.payment_date, 'YYYY-MM-DD').format('DD.MM.YYYY') : '';
            var percentageDue = (offer.percentage_due != '' && offer.percentage_due != null) ? offer.percentage_due : '';
            var discount = (offer.discount != '' && offer.discount != null) ? offer.discount : '';
            var bookingType = offer.booking_type == 1 ? 'Date Range' : 'Days prior to the check-in';
            if (offer.booking_type == 1) {
                bookingDateFrom = (offer.booking_date_from != '' && offer.booking_date_from != null) ? moment(offer.booking_date_from, 'YYYY-MM-DD').format('DD.MM.YYYY') : '';
                bookingDateTo = (offer.booking_date_to != '' && offer.booking_date_to != null) ? moment(offer.booking_date_to, 'YYYY-MM-DD').format('DD.MM.YYYY') : '';
                rowBookingTypeData =
                    '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label>Booking Date From</label>' +
                    '<input type="text" class="form-control" name="booking-date-from" value="' + bookingDateFrom + '" readonly>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label>Booking Date To</label>' +
                    '<input type="text" class="form-control" name="booking-date-to" value="' + bookingDateTo + '" readonly>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }
            else {
                daysPriorFrom = (offer.days_prior_from != '' && offer.days_prior_from != null) ? offer.days_prior_from : '';
                daysPriorTo = (offer.days_prior_to != '' && offer.days_prior_to != null) ? offer.days_prior_to : '';
                rowBookingTypeData =
                    '<div class="row">' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label>Days From</label>' +
                    '<input type="text" class="form-control" name="days-prior-from" value="' + daysPriorFrom + '" readonly>' +
                    '</div>' +
                    '</div>' +
                    '<div class="col-md-6">' +
                    '<div class="form-group">' +
                    '<label>Days To</label>' +
                    '<input type="text" class="form-control" name="days-prior-to" value="' + daysPriorTo + '" readonly>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }
            var discountType = offer.discount_type == 1 ? 'Percent' : 'Fee';
            var minimumStay = (offer.minimum_stay != '' && offer.minimum_stay != null) ? offer.minimum_stay : '';
            var html =
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Non Refundable' +
                '<input type="checkbox" value="1" name="non-refundable" onclick="return false;" ' + (offer.non_refundable == 1 ? 'checked' : '') + '/>' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom"> Apply with other offers' +
                '<input type="checkbox" value="1" name="apply-with-other-offers" onclick="return false;" ' + (offer.apply_with_other_offers == 1 ? 'checked' : '')  + '/>' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Minimum Stay</label>' +
                '<input type="text" class="form-control" name="minimum-stay" value="' + minimumStay + '" readonly>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Booking Type</label>' +
                '<input type="text" class="form-control" name="booking-type" value="' + bookingType + '" readonly>' +
                '</div>' +
                '</div>' +
                '</div>' +
                rowBookingTypeData +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Payment Date</label>' +
                '<input type="text" class="form-control" name="payment-date" value="' + paymentDate + '" readonly>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Percentage Due</label>' +
                '<input type="text" class="form-control" name="percentage-due" value="' + percentageDue + '" readonly>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="row">' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Discount</label>' +
                '<input type="text" class="form-control" name="discount" value="' + discount + '" readonly>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<label>Discount Type</label>' +
                '<input type="text" class="form-control" name="discount-type" value="' + discountType + '" readonly>' +
                '</div>' +
                '</div>' +
                '</div>'
            ;
            $('#modal-info-offer .offer-input-container').append(html);
        }
    });

    var tableOfferRoomType = $('#modal-add-offer .table-room-type').dataTable({
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

    tableOfferRoomType.find('.group-checkable').change(function () {
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
        $('#modal-add-offer :input[name=count-offer-room-type]').val(countSelectedRecords(tableOfferRoomType));
    });

    tableOfferRoomType.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass("active");
        $('#modal-add-offer :input[name=count-offer-room-type]').val(countSelectedRecords(tableOfferRoomType));
    });

    $('#modal-add-offer :input[name=search-code]').on('keyup', function() {
        tableOfferRoomType.api()
            .columns(2).search($('#modal-add-offer :input[name="search-code"]').val())
            .columns(3).search($('#modal-add-offer :input[name="search-name"]').val())
            .draw();
    });

    $('#modal-add-offer :input[name=search-name]').on('keyup', function() {
        tableOfferRoomType.api()
            .columns(2).search($('#modal-add-offer :input[name="search-code"]').val())
            .columns(3).search($('#modal-add-offer :input[name="search-name"]').val())
            .draw();
    });

    var tableInfoOfferRoomType = $('#modal-info-offer .table-room-type').dataTable({
        "sDom": "tip",
        "autoWidth": false,
        "order": [[ 1, "asc" ]],
        "lengthMenu": [[-1], ["All"]],
        "pageLength": 10
    });

    var tableEditOfferRoomType = $('#modal-edit-offer .table-room-type').dataTable({
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

    tableEditOfferRoomType.find('.group-checkable').change(function () {
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
        $('#modal-edit-offer :input[name=count-offer-room-type]').val(countSelectedRecords(tableEditOfferRoomType));
    });

    tableEditOfferRoomType.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass("active");
        $('#modal-edit-offer :input[name=count-offer-room-type]').val(countSelectedRecords(tableEditOfferRoomType));
    });

    $('#modal-edit-offer :input[name=search-code]').on('keyup', function() {
        tableEditOfferRoomType.api()
            .columns(2).search($('#modal-edit-offer :input[name="search-code"]').val())
            .columns(3).search($('#modal-edit-offer :input[name="search-name"]').val())
            .draw();
    });

    $('#modal-edit-offer :input[name=search-name]').on('keyup', function() {
        tableEditOfferRoomType.api()
            .columns(2).search($('#modal-edit-offer :input[name="search-code"]').val())
            .columns(3).search($('#modal-edit-offer :input[name="search-name"]').val())
            .draw();
    });

    var tableOfferBoardType = $('#modal-add-offer .table-board-type').dataTable({
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

    tableOfferBoardType.find('.group-checkable').change(function () {
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
        $('#modal-add-offer :input[name=count-offer-board-type]').val(countSelectedRecords(tableOfferBoardType));
    });

    tableOfferBoardType.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass("active");
        $('#modal-add-offer :input[name=count-offer-board-type]').val(countSelectedRecords(tableOfferBoardType));
    });

    var tableInfoOfferBoardType = $('#modal-info-offer .table-board-type').dataTable({
        "sDom": "tip",
        "autoWidth": false,
        "order": [[ 1, "asc" ]],
        "lengthMenu": [[-1], ["All"]],
        "pageLength": 10
    });

    var tableEditOfferBoardType = $('#modal-edit-offer .table-board-type').dataTable({
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

    tableEditOfferBoardType.find('.group-checkable').change(function () {
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
        $('#modal-edit-offer :input[name=count-offer-board-type]').val(countSelectedRecords(tableEditOfferBoardType));
    });

    tableEditOfferBoardType.on('change', 'tbody tr .checkboxes', function () {
        $(this).parents('tr').toggleClass("active");
        $('#modal-edit-offer :input[name=count-offer-board-type]').val(countSelectedRecords(tableEditOfferBoardType));
    });

    $('.add-offer').on('click', function (e) {
        resetAddOffer();
    });

    var requestDeleteOffer = false;
    $('#table-offer tbody').on( 'click', '.dt-delete', function (e) {
        if (!requestDeleteOffer) {
            var data = tableOffer.api().row( $(this).parents('tr') ).data();
            $(this).confirmation('show');
            $(this).on('confirmed.bs.confirmation', function () {
                requestDeleteOffer = true;
                $.ajax({
                    url: routeDeleteOffer,
                    "type": "POST",
                    "data":  {
                        id: data['0']
                    },
                    "beforeSend": function() {
                        App.showMask(true, $('#table-offer'));
                    },
                    "complete": function(xhr, textStatus) {
                        requestDeleteOffer = false;
                        App.showMask(false, $('#table-offer'));
                        if (xhr.status == '419') {
                            location.reload(true);
                        }
                        else if (xhr.status != '200') {
                            toastr['error']("Please check your connection and try again.", "Error on loading the content");
                        }
                        else {
                            var response = $.parseJSON(xhr.responseText);
                            if (response.status == 'success') {
                                if (searched) {
                                    needUpdate = true;
                                }
                                var offers = response.data;
                                loadOffers(offers);
                                toastr['success'](response.message, "Success");
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

    $('.btn-complements').on('click', function () {
        $('#modal-complements').modal('show');
    });

    $('.check-all-rooms').on('click', function (e) {
        e.preventDefault();
        var option = $(this).attr('data');
        if (option == 'check') {
            $(this).attr('data', 'uncheck');
            $('.room-selected').each(function () {
                $(this).prop("checked", false);
            });
        }
        else {
            $(this).attr('data', 'check');
            $('.room-selected').each(function () {
                $(this).prop("checked", true);
            });
        }
    });

    function resetAddOffer() {
        formAddOffer.validate().resetForm();
        formAddOffer[0].reset();
        $('#modal-add-offer .range-optional').each(function () {
            $(this).remove();
        });

        $('#modal-add-offer .expand').each(function () {
            $(this).click();
        });

        $('#modal-add-offer :input[name=offer-type]').val('').change();

        var roomTypes = contract.room_types;
        tableOfferRoomType.api().clear();
        for (var i = 0; i < roomTypes.length; i++) {
            tableOfferRoomType.api().row.add([
                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                '<input type="checkbox" class="checkboxes" value="1" />' +
                '<span></span>' +
                '</label>',
                roomTypes[i].id,
                roomTypes[i].code,
                roomTypes[i].name
            ]).draw( false );
        }
        $('#modal-complements :input[name=count-offer-room-type]').val(0);
        tableOfferRoomType.api().columns.adjust().draw();

        var boardTypes = contract.board_types;
        tableOfferBoardType.api().clear();
        for (var i = 0; i < boardTypes.length; i++) {
            tableOfferBoardType.api().row.add([
                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                '<input type="checkbox" class="checkboxes" value="1" />' +
                '<span></span>' +
                '</label>',
                boardTypes[i].id,
                boardTypes[i].code,
                boardTypes[i].name
            ]).draw( false );
        }
        $('#modal-complements :input[name=count-offer-board-type]').val(0);
        tableOfferBoardType.api().columns.adjust().draw();
    }
});
