@extends('layouts.master2')
@section('title','Hotel Contract Settings')
@section('page-css')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<style>
.table-setting td { font-size: 11px !important; padding: 5px 1px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
.table-setting th { font-size: 11px !important; padding: 5px 2px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
.table-setting { margin-bottom: 0; table-layout: fixed !important; min-width: 900px; border-bottom: 0;}
.porlet-title-setting { min-height: 0 !important; height: 30px; }
.caption-setting { font-size: 13px !important; padding: 6px 0 5px !important; font-weight: 600; }
.tools-setting { font-size: 13px !important; padding: 6px 0 0 !important; }
.table-setting .item-setting:hover { background-color: #f2f2f2; cursor: pointer; }
.column-setting { width: 2.9%; }
.head-setting { vertical-align: top !important; background-color: #e8f0fc; border:1px solid #fff !important; }
.head-setting-invalid { background-color: #fff !important; border: 1px solid #fff !important;}
.room-name { word-wrap:break-word;width: 10.1%; color: #fff; background-color: #6d90c4;white-space: normal !important; vertical-align: middle !important;}
.item-variable { /*font-weight: 600;background-color: #e8f0fc; border:1px solid #fff !important;*/}
/*.room-name { word-wrap:break-word;width: 10.1%;}*/
/*.select2-selection__rendered { margin-left: 20px; }*/
.mt-checkbox-row { margin-bottom: 10px !important; }
/*.mt-checkbox-list-row { padding: 0 !important; }*/
.portlet-body-row { padding-top: 5px !important; padding-bottom: 5px !important }
/*.btn-search-submit { margin-top: 10px; }*/
/*.porlet-setting { margin-bottom: 5px !important;}*/
/*.medium-porlet { min-height: 0 !important; height: 30px; }*/
.mt-radio { margin-bottom: 10px !important; }
.note-custom { padding: 4px 10px !important; margin-bottom: 5px !important; }
</style>
@stop

@section('page-title','Hotel Contract Settings')
@section('page-sub-title','define prices, allotments and more...')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container" style="padding-bottom: 0;">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings"></i>Search Accommodation </div>
            </div>
            <form id="search-accomodation">
            <div class="portlet-body">
                <div class="row filter-content" style="display: none;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="portlet box green">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-building-o"></i>Contract</div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <label>Contract</label>
                                        <div class="form-group">
                                            <select class="form-control" name="contract"></select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <label>Hotel</label>
                                            <input type="text" class="form-control" name="hotel" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <label>Period</label>
                                            <input type="text" class="form-control" name="period" readonly style="background-color: #fff;">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div class="form-group">
                                            <label>Price Rate</label>
                                            <select class="form-control" name="market" id="market"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-calendar"></i>Range Date</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12 datepicker-from-container" style="margin-top: 8px;"></div>
                                        <div class="col-md-12 datepicker-to-container"></div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn green btn-search-submit" style="margin-top:5px;"> <i class="fa fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-hotel"></i>Rooms</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row room-types-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-table"></i>Rows</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row measures-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 result-container"></div>
</div>

<div id="modal-setting" class="modal fade custom-container" tabindex="-1" data-width="550" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="icon-settings"></i> Settings</h4>
    </div>
    <form id="form-add">
    <div class="modal-body">
        <input type="hidden" name="contract-id" value="0">
        <input type="hidden" name="room-type-id" value="0">
        <input type="hidden" name="market-id" value="0">
        <div class="row">
            <div class="col-md-12" style="margin-bottom: 20px;">
                <span class="caption-subject font-green-sharp bold uppercase room-name-header" style="font-size: 16px;"></span>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <label>From</label>
                    <div class="input-icon left">
                        <i class="fa fa-calendar"></i>
                        <input type="text" class="form-control date-picker" name="setting-from">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6">
                <div class="form-group">
                    <label>To</label>
                    <div class="input-icon left">
                        <i class="fa fa-calendar"></i>
                        <input type="text" class="form-control date-picker" name="setting-to">
                    </div>
                </div>
            </div>
        </div>
        <div class="row measures-container"></div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn green" data="accept"><i class="fa fa-check"></i> Accept</button>
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
    </form>
</div>

@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
@stop

@section('custom-scripts')
<script>
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
        var contractId = '{{ $contract_id }}';
        var contract = null;

        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy'
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
                url: "{{ route('hotel.contract.search') }}",
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
            "url": "{{ route('hotel.contract') }}",
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

        $(".datepicker").datepicker({
            format: "MM yyyy",
            viewMode: "months",
            minViewMode: "months",
            autoclose: true
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
                    "url": "{{ route('hotel.contract.settings.data') }}",
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

            $('#market').empty()
            $.each(markets, function (i, item) {
                var option = '<option value="' + markets[i].id + '"> ' + markets[i].name + '</option>';
                var option = '<option value="' + markets[i].id + '"> ' + markets[i].name + '</option>';
                $('#market').append(option);
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
                autoclose: true
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
            else {
                var tempStart = moment(startDate).startOf('month');
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
                        //$('input[name=' + name + ']').val('');
                    }
                });
            }
        }

        function renderTable(from, to, contract) {
            $('.item-setting').on('click', function() {
                formAdd.validate().resetForm();
                formAdd[0].reset();
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
                $('#modal-setting').modal('show');
            });
        }

        var formAdd = $('#form-add');
        formAdd.validate({
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
                $.ajax({
                    "url": "{{ route('hotel.contract.settings.save') }}",
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

        $('.cancel-form').on('click', function(e) {
            if(needUpdate) {
                $(".btn-search-submit").click();
                needUpdate = false;
            }
        });
    });
</script>
@stop