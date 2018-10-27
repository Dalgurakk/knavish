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

    $('.date-picker').datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: true,
        format: 'dd.mm.yyyy',
        orientation: "bottom"
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

    function setValues(data) {
        for (var i = 0; i < data.length; i++) {
            $('[data-date=' + data[i].date + '][data-measure=' + data[i].measure + '][data-room-type=' + data[i].room + ']').html(data[i].value);
        }
    }

    function fillContract(c) {
        var roomTypes = c.room_types;
        var measures = c.measures;
        var markets = c.markets;
        var contract = c;
        var status = contract.active == 1 ? 'Enabled' : 'Disabled';
        $("#search-accomodation :input[name=hotel]").val(contract.hotel.name);
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
            var tempEnd = moment(endDate
            ).endOf('month');
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
                        '<div class="col-md-5 col-sm-5 col-xs-5">' +
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
                        '<div class="col-md-5 col-sm-5 col-xs-5">' +
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
        $('#price-rate').empty();
        $('.import').remove();
        $("#market option:not(:selected)").each(function(i){
            var option = '<option value="' + $(this).val() + '"> ' + $(this).text() + '</option>';
            $('#price-rate').append(option);
        });

        if (markets.length > 1) {
            $('.room-name-header').append(
                '<a class="btn btn-circle btn-icon-only btn-default btn-outline import hide-import" style="margin-left: 10px; margin-bottom: 7px;" href="javascript:;">' +
                    '<i class="fa fa-arrow-down"></i>' +
                '</a>'
            );

            $('.import').on('click', function (e) {
                e.preventDefault();
                if ($(this).hasClass('show-import')) {
                    $('.set-price-container').css('display', 'block');
                    $('.import-cost-container').css('display', 'none');
                    formSetting.validate();
                    $('input[name="cost"]').rules('add', 'required');
                    $(this).removeClass('show-import');
                    $(this).addClass('hide-import');
                    $('input[name=import-cost]').val('');
                }
                else {
                    $('.set-price-container').css('display', 'none');
                    $('.import-cost-container').css('display', 'block');
                    formSetting.validate();
                    $('input[name="cost"]').rules('remove', 'required');
                    $(this).addClass('show-import');
                    $(this).removeClass('hide-import');
                    $('input[name=import-cost]').val('1');
                }
            });
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

            $('.set-price-container').css('display', 'block');
            $('.import-cost-container').css('display', 'none');
            $('input[name=import-cost]').val('');

            $('#modal-setting').modal('show');
        });
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
        });

        $('input[name="setting-to-' + time + '"]').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
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
});