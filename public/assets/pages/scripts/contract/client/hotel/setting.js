$(document).ready(function () {
    var formSearch = $('#search-accomodation');
    var contract = null;

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
            fillContract(value);
            var url = window.location.href;
            if (url.indexOf("?") > 0) {
                var updatedUri = url.substring(0, url.indexOf("?"));
                window.history.replaceState({}, document.title, updatedUri);
            }
        }
    });

    $.ajax({
        "url": routeClient,
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
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            var table = response.table;
                            $('.result-container').html('');
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
            var measure =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" name="row-selected" checked value="' + measures[i].id + '"> ' + measures[i].name +
                '<span></span>' +
                '</label>';
            $('.measures-list').append(measure);
        });

        $('.room-types-list').html('');
        $.each(roomTypes, function (i, item) {
            var roomType =
                '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                '<input type="checkbox" name="room-selected" checked value="' + roomTypes[i].id + '"> ' + roomTypes[i].name +
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

        if (currentDate.isSameOrBefore(endDate) && currentDate.isSameOrAfter(startDate)){
            var tempStart = moment(currentDate).startOf('month');
            var tempEnd = moment(currentDate).endOf('month');
            $('input[name=from]').datepicker( "setDate" , new Date(tempStart));
            $('input[name=to]').datepicker( "setDate" , new Date(tempEnd));
        }
        else if (currentDate.isBefore(startDate)) {
            var tempStart = moment(startDate).startOf('month');
            var tempEnd = moment(startDate).endOf('month');
            $('input[name=from]').datepicker( "setDate" , new Date(tempStart));
            $('input[name=to]').datepicker( "setDate" , new Date(tempEnd));
        }
        else if (currentDate.isAfter(endDate)) {
            var tempStart = moment(endDate).startOf('month');
            var tempEnd = moment(endDate
            ).endOf('month');
            $('input[name=from]').datepicker( "setDate" , new Date(tempStart));
            $('input[name=to]').datepicker( "setDate" , new Date(tempEnd));
        }
        $('input[name=from]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name=from]').datepicker( "setEndDate" , new Date(endDate));
        $('input[name=to]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name=to]').datepicker( "setEndDate" , new Date(endDate));

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

    function renderTable(from, to, contract) {
        $('.item-setting').on('click', function() {
            var room = $(this).parents('table').find('th:first').html();
            var date = $(this).attr('data-date');
            var measure = $(this).parents('tr').find('td:first').attr('data-measure-code');

            for (var i = 0; i < contract.measures.length; i++) {
                var date = $(this).attr('data-date');
                var measureId = contract.measures[i].id;
                var value = $(this).parents('table').find('td[data-date="' + date + '"][data-measure-id="' + measureId + '"]').html();
            }
        });
    }
});