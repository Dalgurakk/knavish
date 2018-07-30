@extends('layouts.master2')
@section('title','Hotel Contract Settings')
@section('page-css')
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.table-setting td { font-size: 11px !important; padding: 5px 2px !important; word-wrap:break-word;white-space: normal !important; text-align: center; }
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
.select2-selection__rendered { margin-left: 20px; }
.mt-checkbox-row { margin-bottom: 5px !important; }
.mt-checkbox-list-row { padding: 0 !important; }
.portlet-body-row { padding-top: 5px !important; padding-bottom: 5px !important }
.btn-search-submit { margin-top: 10px; }
.porlet-setting { margin-bottom: 5px !important;}
/*.medium-porlet { min-height: 0 !important; height: 30px; }*/
</style>
@stop

@section('page-title','Hotel Contract Settings')
@section('page-sub-title','define prices, allotments and more...')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings"></i>Search Accommodation</div>
            </div>
            <form id="search-accomodation">
            <div class="portlet-body">
                <div class="row filter-content" style="display: none;">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="portlet box green">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-building-o"></i>Hotel</div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4">
                                        <label>Hotel</label>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-building-o"></i>
                                                <select class="form-control" name="hotel"></select> </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <label>Contract</label>
                                        <div class="form-group">
                                            <div class="input-icon">
                                                <i class="fa fa-file-text-o"></i>
                                                <select class="form-control" name="contract"></select> </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <div class="form-group">
                                            <div class="mt-checkbox-list">
                                                <label class="mt-checkbox mt-checkbox-outline no-margin-bottom" style="margin-top: 15px;"> Load old contracts
                                                    <input type="checkbox" value="1" name="old"/>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-calendar"></i>Range Date</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12 datepicker-from-container"></div>
                                        <div class="col-md-12 datepicker-to-container"></div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn green btn-search-submit"><i class="fa fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                        <!--div class="portlet light bordered"-->
                            <div class="portlet-title porlet-title-setting">
                                <div class="caption caption-setting">
                                    <i class="fa fa-table"></i>Rows</div>
                            </div>
                            <div class="portlet-body" style="padding-bottom: 8px;">
                                <div class="scroller" style="height:200px">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mt-checkbox-list mt-checkbox-list-row rows-list"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="portlet box green porlet-setting">
                        <!--div class="portlet light bordered"-->
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
                </div>
                <!--div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">

                    </div>
                </div-->
            </div>
            </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 result-container"></div>
</div>


@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
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

        var roomTypes = [];
        var contracts = [];
        var contract;
        var rows = [
            { id: 'cost', name: 'Cost', selected: true},
            { id: 'price', name: 'Price', selected: true},
            { id: 'allotment', name: 'Allotment', selected: true},
            { id: 'release', name: 'Release', selected: true},
            { id: 'offer', name: 'Offer', selected: true},
            { id: 'stop_sale', name: 'Stop Sale', selected: true},
            { id: 'restriction', name: 'Restriction', selected: true},
            { id: 'supplement', name: 'Supplement', selected: true}
        ];

        $.ajax({
            "url": "{{ route('hotel.search.contract.active') }}",
            "type": "POST",
            "data": {
                first: "true"
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
                    var hotel = response[0];
                    contracts = hotel.contracts;

                    $(".select2-selection__rendered").html(hotel.name);
                    if(contracts.length >= 1) {
                        fillContract(contracts[0]);

                        for(var i = 0; i < contracts.length; i++) {
                            $('#search-accomodation :input[name=contract]').append($('<option>', {
                                value: contracts[i].id,
                                text : contracts[i].name
                            }));
                        }
                    }
                    
                    $('.filter-content').show();
                }
            }
        });

        $.each(rows, function (i, item) {
            if(i == 0) {
                $('.rows-list').html('');
                $.each(rows, function (i, item) {
                    var row =
                        '<label class="mt-checkbox mt-checkbox-outline mt-checkbox-row">' +
                            '<input type="checkbox" checked name="row-selected" value="' + rows[i].id + '"> ' + rows[i].name +
                            '<span></span>' +
                        '</label>';
                    $('.rows-list').append(row);
                });
            }
        });

        $('input[name=row-selected]').on('click', function() {
            var $items = $('tr[data-row="' + $(this).val() + '"]');
            if ($(this).is(':checked')) {
                $items.each(function(){
                    $(this).show();
                });
            }
            else {
                $items.each(function(){
                    $(this).hide();
                });
            }
        });

        var formSearch = $('#search-accomodation');
        $('.btn-search-submit').on('click', function(e) {
            e.preventDefault();
            if(contract == null) {
                toastr['error']('Invalid contract.', "Error");
            }
            else {
                var from = moment($('input[name=from]').datepicker("getDate")).format('DD-MM-YYYY');
                var to = moment($('input[name=to]').datepicker("getDate")).format('DD-MM-YYYY');
                $.ajax({
                    "url": "{{ route('hotel.contract.settings') }}",
                    "type": "POST",
                    "data": {
                        id: contract.id,
                        from:  from,
                        to: to
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
                                renderTable(response.from, response.to, response.data);
                            }
                            else {
                                toastr['error'](response.message, "Error");
                            }
                        }
                    }
                });
            }
        });

        function formatHotel(repo) {
            if (repo.loading) return repo.text;
            var markup =
                "<div class=''>" +
                    "<div class=''>" + repo.name + "</div>"+
                "</div>";
            return markup;
        }

        function formatHotelSelection(repo) {
            return repo.name;
        }

        $("#search-accomodation :input[name=hotel]").select2({
            width: "off",
            placeholder: "<i class='icon-group'></i> &nbsp;&nbsp; inout your tags...",
            ajax: {
                url: "{{ route('hotel.search.contract.active') }}",
                "type": "POST",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        page: params.page,
                        first: "false"
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
            templateResult: formatHotel,
            templateSelection: formatHotelSelection
        });

        $("#search-accomodation :input[name=hotel]").on('select2:select select2:unselect', function (e) {
            var values = e.params.data;
            //console.log(values);
            contracts = [];
            if(values.selected) {
                contracts = values.contracts;
                $('#search-accomodation :input[name=contract]').empty();
                $.each(contracts, function (i, item) {
                    if(i == 0) {
                        fillContract(contracts[i]);
                    }

                    $('#search-accomodation :input[name=contract]').append($('<option>', {
                        value: item.id,
                        text : item.name
                    }));
                });
            }
        });

        $('#search-accomodation :input[name=contract]').change(function() {
            for(var i = 0; i < contracts.length; i++) {
                if(contracts[i].id == $(this).val()) {
                    fillContract(contracts[i]);
                    break;
                }
            }
        });

        function fillContract(c) {
            roomTypes = c.room_types;
            contract = c;
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
        }

        function renderTable(from, to, data) {
            $('.result-container').html('');
            var start = moment(from, 'DD-MM-YYYY').startOf('month');
            var end = moment(to, 'DD-MM-YYYY').endOf('month');
            var html = '';

            for (var m = start; m.isSameOrBefore(end); m.add(1, 'month')) {
                html =
                '<div class="portlet box green">' +
                    '<div class="portlet-title porlet-title-setting">' +
                        '<div class="caption caption-setting">' +
                            '<!--i class="fa fa-calendar"></i-->' + m.format("MMMM YYYY") + '</div>' +
                        '<div class="tools tools-setting">' +
                            '<a href="" class="fullscreen"> </a>' +
                            '<a href="javascript:;" class="collapse"> </a>' +
                        '</div>' +
                    '</div>' +
                    '<div class="portlet-body" style="padding: 0;">' +
                        '<div class="table-responsive">';

                for (var r = 0; r < roomTypes.length; r++) {
                    html +=
                            '<table class="table table-striped table-bordered table-setting" data-room="' + roomTypes[r].id + '">' +
                                '<thead>' +
                                    '<tr>' +
                                        '<th class="room-name head-setting">' + roomTypes[r].name.toUpperCase() + '</th>';

                    var monthStart = moment(m, 'YYYY-MM-DD').startOf('month');
                    var monthEnd = moment(m, 'YYYY-MM-DD').endOf('month');
                    var count = 0;

                    for (var d = monthStart; d.isSameOrBefore(monthEnd); d.add(1, 'days')) {
                        html +=
                                        //'<th class="column-setting head-setting">' + d.format('D') + '<br>' + d.format('dd') + '</th>';
                                        '<th class="column-setting head-setting">' + d.format('D') + '</th>';
                        count ++;
                    }
                    if (count < 31) {
                        for (var z = count; z < 31; z++) {
                            html +=
                                        '<th class="column-setting head-setting-invalid"></th>';
                        }
                    }
                    html +=
                                    '</tr>' +
                                '</thead>' +
                                '<tbody>';

                    for (var v = 0; v < rows.length; v++) {
                        html +=
                                    '<tr data-row="' + rows[v].id + '">' +
                                        '<td class="column-setting item-variable">' + rows[v].name.toUpperCase() + '</td>';

                        monthStart = moment(m, 'YYYY-MM-DD').startOf('month');
                        monthEnd = moment(m, 'YYYY-MM-DD').endOf('month');

                        for (var i = monthStart; i.isSameOrBefore(monthEnd); i.add(1, 'days')) {
                            html +=
                                        '<td class="column-setting item-setting" data-date="' + i.format('YYYY-MM-DD') + '" data-row="' + rows[v].id + '" data-room-type="' + roomTypes[r].name + '">' + '5' + '</td>';
                        }
                        html +=
                                    '</tr>';
                    }
                    html +=
                                '</tbody>' +
                            '</table>';
                }
                html +=
                        '</div' +
                    '</div' +
                '</div>';

                $('.result-container').append(html);
            }

            $('input[name="room-selected"]').on('click', function() {
                var $items = $('table[data-room="' + $(this).val() + '"]');

                if ($(this).is(':checked')) {
                    $items.each(function(){
                        $(this).show();
                    });
                }
                else {
                    $items.each(function(){
                        $(this).hide();
                    });
                }
            });

            $('.item-setting').on('click', function() {
                alert($(this).html());
            });

            $('input[name=row-selected]:checked').each(function() {
                var $items = $('tr[data-row="' + $(this).val() + '"]');
                $items.each(function(){
                    $(this).show();
                });
            });

            $('input[name=row-selected]:not(:checked)').each(function() {
                var $items = $('tr[data-row="' + $(this).val() + '"]');
                $items.each(function(){
                    $(this).hide();
                });
            });

            $('input[name=room-selected]:checked').each(function() {
                var $items = $('table[data-room="' + $(this).val() + '"]');
                $items.each(function(){
                    $(this).show();
                });
            });

            $('input[name=room-selected]:not(:checked)').each(function() {
                var $items = $('table[data-room="' + $(this).val() + '"]');
                $items.each(function(){
                    $(this).hide();
                });
            });
            //$('[data-date="2018-07-02"][data-row="Stop Sale"]').html('es');
        }
    });
</script>
@stop