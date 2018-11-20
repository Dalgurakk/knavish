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
                        url: routeImport,
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
            var tempEnd = moment(endDate).endOf('month');
            $('input[name=from]').datepicker( "setDate" , new Date(tempStart));
            $('input[name=to]').datepicker( "setDate" , new Date(tempEnd));
        }
        $('input[name=from]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name=from]').datepicker( "setEndDate" , new Date(endDate));
        $('input[name=to]').datepicker( "setStartDate" , new Date(startDate));
        $('input[name=to]').datepicker( "setEndDate" , new Date(endDate));

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
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Set' +
                                    '<input type="checkbox" value="1" name="set-' + contract.measures[i].code + '" data-set="' + contract.measures[i].code + '" data-measure-id="' + contract.measures[i].id + '" />' +
                                    '<span></span>' +
                                '</label>' +
                                '<label class="mt-checkbox mt-checkbox-outline no-margin-bottom margin-top-15 margin-right-40"> Unset' +
                                    '<input type="checkbox" value="0" name="unset-cost" />' +
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
                                    '<input type="checkbox" value="" name="set-' + contract.measures[i].code + '" data-set="' + contract.measures[i].code + '" data-measure-id="' + contract.measures[i].id + '" />' +
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
        }

        $('input[name="unset-cost"]').change(function() {
            if($(this).is(":checked")) {
                $('.measure-input').prop('readonly', true);
                $('.measure-input').val('');
                $('input[name^=set-]').prop('checked', '');
                $('input[name^=set-]').attr('onclick', 'return false;');
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

            $('#modal-setting').modal('show');
        });
    }

    function getSelectedRows(table) {
        var rows = [];
        /*$('tbody > tr > td:nth-child(1) input[type="checkbox"]:checked', table).each(function() {
            var data = table.api().row( $(this).parents('tr') ).data();
            rows.push(data[1]);
        });
        return rows;*/
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
                            formSetting[0].reset();
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
            if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
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
            if (start != 0 && start != '' && moment(endDate).isBefore(startDate)){
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
});
