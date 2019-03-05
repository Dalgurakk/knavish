$(document).ready(function () {
    var formSearch = $('#search-accomodation');
    var contract = null;
    var searched = false;

    getContract();

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

    $('#board-type').change(function() {
        if(searched) {
            $('.btn-search-submit').click();
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
                                '<p>Pricing are all per person per night.</p>' +
                                '</div>'
                            );
                            $('.result-container').append(table);
                            operateTable(response.from, response.to, contract);

                            var offers = response.offers;
                            contract.hotel_contract.offers = offers;

                            $('.complement').each(function () {
                                var code = $(this).attr('data-measure-code');
                                var data = $(this).attr('data');
                                if (code == 'offer' && data != '') {
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
                                                table +=
                                                    '<tr><td>' + (i + 1) + '</td><td>' + offers[j].name + '</td><td>' + offers[j].offer_type.name + '</td></tr>';
                                                break;
                                            }
                                        }
                                    }
                                    table +=
                                        '</tbody>' +
                                        '</table>';
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

    function fillContract(c) {
        var roomTypes = c.hotel_contract.room_types;
        var boardTypes = c.hotel_contract.board_types;
        var measures = c.hotel_contract.measures;
        var contract = c.hotel_contract;
        var client = c.client;
        var status = contract.active == 1 ? 'Enabled' : 'Disabled';
        $("#search-accomodation :input[name=hotel]").val(contract.hotel.name);
        $("#search-accomodation :input[name=hotel-chain]").val(contract.hotel.hotel_chain.name);
        $("#search-accomodation :input[name=status]").val(status);
        $("#search-accomodation :input[name=client]").val(client.name);
        $("#search-accomodation :input[name=period]").val(moment(contract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY') + ' - ' + moment(contract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
        $('.result-container').html('');
        $('.measures-list').html('');
        $.each(measures, function (i, item) {
            var measure =
                '<div class="row">' +
                '<div class="col-md-5">' +
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" class="row-selected" checked value="' + measures[i].id + '"> ' + measures[i].name +
                '<span></span>' +
                '</label>' +
                '</div>';
            if (measures[i].code == 'price' || measures[i].code == 'allotment') {
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

        $('#board-type').empty();
        $.each(boardTypes, function (i, item) {
            var option = '<option value="' + boardTypes[i].id + '"> ' + boardTypes[i].code + ': ' + boardTypes[i].name + '</option>';
            $('#board-type').append(option);
        });

        $('.room-types-list').html('');
        $.each(roomTypes, function (i, item) {
            var roomType =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" class="room-selected" checked value="' + roomTypes[i].id + '"> ' + roomTypes[i].code + ': ' + roomTypes[i].name +
                '<span></span>' +
                '</label>';
            $('.room-types-list').append(roomType);
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

        $('.measures-container').html('');

        for (var i = 0; i < contract.measures.length; i++) {
            var html =
                '<div class="row">' +
                '<div class="col-md-12">' +
                '<div class="col-md-6 col-sm-6 col-xs-6">' +
                '<div class="form-group">' +
                '<label>' + contract.measures[i].name + '</label>' +
                '<input type="text" class="form-control" name="' + contract.measures[i].code + '" readonly>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-6 col-sm-6 col-xs-6">' +
                '<div class="mt-checkbox-list">' +
                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15"> Set' +
                '<input type="checkbox" value="" name="set-' + contract.measures[i].code + '" data-set="' + contract.measures[i].code + '" data-measure-id="' + contract.measures[i].id + '" />' +
                '<span></span>' +
                '</label>' +
                '</div>' +
                '</div>' +
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
                }
            });
        }
    }

    function operateTable(from, to, contract) {
        $('.item-setting').on('click', function() {
            if ($(this).hasClass('complement')) {
                var code = $(this).attr('data-measure-code');
                if (code == 'offer') {
                    var data = $(this).attr('data');
                    var offersInDay = JSON.parse("[" + $(this).attr('data') + "]");
                    if (offersInDay.length > 0) {
                        var offers = contract.hotel_contract.offers;
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
                                        '<i class="fa fa-cogs"></i>' + offers[j].name + '</div>' +
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