@extends('layouts.master2')
@section('title','Contracts')
@section('page-css')
<link href="{{ asset('assets/global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-bar-rating-master/dist/themes/fontawesome-stars.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/jquery-multi-select/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.css') }}" rel="stylesheet" type="text/css" />
<style>
    .ms-container .ms-list { height: 150px; }
    .ms-container { width: unset; }
    .tabbable-custom { margin-bottom: 0; }
    .tabbable-line > .nav-tabs > li { border-bottom: 0 !important; }
    .custom-radio { margin-bottom: 11px !important; }
    .tabbable-custom { margin-bottom: 0px; }
    .ms-container .ms-selectable li.ms-elem-selectable { padding: 5px 10px !important; }
    .ms-container .ms-selection li.ms-elem-selection { padding: 5px 10px !important; }
</style>
@stop

@section('page-title','Hotel Contracts')
@section('page-sub-title','')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="portlet light custom-container">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-file-text-o"></i>Contracts List </div>
                <div class="actions">
                    <a class="btn btn-circle btn-icon-only btn-default search" href="javascript:;">
                        <i class="fa fa-search"></i>
                    </a>
                    <a class="btn btn-circle btn-icon-only btn-default reload" href="javascript:;">
                        <i class="fa fa-refresh"></i>
                    </a>
                    <div class="btn-group">
                        <a class="btn btn-circle btn-icon-only btn-default dropdown-toggle lenght btn-dropdown" data-toggle="dropdown" href="javascript:;">10</a>
                        <ul class="dropdown-menu dropdown-options">
                            <li>
                                <a href="javascript:;" class="lenght-option" data="10">10</a>
                            </li>
                            <li>
                                <a href="javascript:;" class="lenght-option" data="25">25</a>
                            </li>
                            <li>
                                <a href="javascript:;" class="lenght-option" data="50">50</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="portlet-body">
                <div id="search-section" style="display: none;">
                    <form>
                        <div class="row">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-file-text-o"></i>
                                        <input type="text" class="form-control" name="name" placeholder="Denomination"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-building-o"></i>
                                        <input type="text" class="form-control" name="hotel" placeholder="Hotel"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" class="form-control date-picker" name="valid-from" placeholder="From"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <div class="input-icon">
                                        <i class="fa fa-calendar"></i>
                                        <input type="text" class="form-control date-picker" name="valid-to" placeholder="To"> </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <button type="submit" class="btn green btn-search-submit"><i class="fa fa-search"></i> Search</button>
                                    <button class="btn default btn-search-reset"><!--i class="fa fa-eraser"></i--> Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <table id="table" class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable" width="100%" cellspacing="0">
                    <thead>
                        <tr role="row" class="heading">
                            <th class="">Id</th>
                            <th class="">Denomination</th>
                            <th class="">Hotel</th>
                            <th class="">Valid From</th>
                            <th class="">Valid To</th>
                            <th class="">Status</th>
                            <th class="" style="min-width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="modal-info" class="modal fade custom-container" tabindex="-1" data-width="760" data-backdrop="static" data-keyboard="false">
    <div class="modal-header">
        <button type="button" class="close cancel-form" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title"><i class="fa fa-file-text-o"></i> Contract Data</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-file-text-o"></i> General Data </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="form-group">
                                    <label>Denomination</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-file-text-o"></i>
                                        <input type="text" class="form-control" name="name" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Valid From</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" type="text" value="" name="valid-from" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Valid To</label>
                                    <div class="input-icon left">
                                        <i class="fa fa-calendar"></i>
                                        <input class="form-control date-picker" type="text" value="" name="valid-to" readonly/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet box green show-hotel">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-building-o"></i> Hotel </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label class="control-label">Hotel</label>
                                    <input type="text" class="form-control trigger-location" name="hotel" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" class="form-control trigger-location" name="country-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control trigger-location" name="state-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control trigger-location" name="city-text" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control" name="address" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Postal Code</label>
                                    <input type="text" class="form-control" name="postal-code" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Turistic License</label>
                                    <input type="text" class="form-control" name="turistic-licence" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label style="margin-bottom: 11px;">Category</label>
                                    <select class="hotel-category" name="category">
                                        <option value="">Select Category</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Hotel Chain</label>
                                    <input type="text" class="form-control" name="hotel-chain" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control" name="email" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Admin. Phone</label>
                                    <input type="text" class="form-control" name="admin-phone" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Admin. Fax</label>
                                    <input type="text" class="form-control" name="admin-fax" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Web Site</label>
                                    <input type="text" class="form-control" name="web-site" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-male"></i> Pax Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-pax-type" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Pax Type </th>
                                    <th> From </th>
                                    <th> To </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-hotel"></i> Room Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-room-type" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Room Type </th>
                                    <th> Max Pax </th>
                                    <th> Min Pax </th>
                                    <th> Min AD </th>
                                    <th> Min CH </th>
                                    <th> Min INF </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="portlet box green ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cutlery"></i> Board Types </div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-group">
                            <table class="table table-striped table-bordered table-hover dt-responsive dt-custom-datatable table-board-type" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th> Id </th>
                                    <th> Board Type </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark cancel-form"><i class="fa fa-close"></i> Cancel</button>
    </div>
</div>
@stop

@section('page-plugins')
<script src="{{ asset('assets/global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/scripts/datatable.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/pages/scripts/table-datatables-responsive.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-bar-rating-master/dist/jquery.barrating.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/my-moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/fuelux/js/spinner.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-touchspin/bootstrap.touchspin.js') }}" type="text/javascript"></script>
@stop

@section('custom-scripts')
<script>
    $(document).ready(function () {
        var needUpdate = false;

        $.fn.dataTable.ext.errMode = 'none';
        var table = $('#table').on('error.dt', function(e, settings, techNote, message) {

        }).on( 'processing.dt', function ( e, settings, processing ) {
            App.showMask(processing, $(this));
            App.reloadToolTips();
        }).on('init.dt', function() {

        }).DataTable({
            "processing": true,
            "serverSide": true,
            "sDom": "ltip",
            "iDisplayLength" : 10,
            "ajax": {
                "url": "{{ route('client.contract.hotel.read') }}",
                "type": "POST",
                "complete": function(xhr, textStatus) {
                    if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    $('.br-readonly').on('click', function(e) {
                        e.preventDefault();
                    });
                }
            },
            "order": [[ 1, "asc" ]],
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'name', name: 'name' },
                { data: 'hotel', name: 'hotel' },
                { data: 'valid_from', name: 'valid_from' },
                { data: 'valid_to', name: 'valid_to' },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    "data": function (row, type, val, meta ) {
                        var data = '';
                        if (row.status == '3')
                            data = '<span class="label label-sm label-danger"> Finished </span>';
                        else if (row.status == '2')
                            data = '<span class="label label-sm label-success"> In Progress </span>';
                        else if (row.status == '1')
                            data = '<span class="label label-sm label-warning"> Pending </span>';
                        return data;
                    }
                },
                {
                    targets: 'actions',
                    orderable: false,
                    name: 'actions',
                    "className": "dt-center",
                    "data": function ( row, type, val, meta ) {
                        //var contract = row.contract;
                        var data =
                        '<form method="get" action="{{ route("client.contract.hotel.settings") }}">' +
                            '<input type="hidden" name="id" value="' + row.id + '">' +
                            '<div class="dt-actions">' +
                            '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info">' +
                                '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>'+
                            '<button type="submit" class="btn btn-default btn-circle btn-icon-only btn-action dt-setting">' +
                                '<i class="icon-settings btn-action-icon"></i></button>' +
                            '</div>' +
                        '</form>';
                        return data;
                    }
                }
            ]
        });

        $('#table_length').hide();

        $('.search').on('click', function (e) {
            $('#search-section').slideToggle();
        });

        $('.reload').on('click', function (e) {
            table.draw();
        });

        $('.lenght-option').on('click', function () {
            var value = $(this).attr('data');
            $(this).parent().parent().prev('a').text(value);
            var select = $('select[name=table_length]');
            select.val(value);
            select.change();
        });

        $('.btn-search-reset').on('click', function (e) {
            e.preventDefault();
            $('#search-section :input[name=name]').val('');
            $('#search-section :input[name=hotel]').val('');
            $('#search-section :input[name=valid-from]').val('');
            $('#search-section :input[name=valid-to]').val('');
        });

        $('.btn-search-submit').on( 'click', function (e) {
            e.preventDefault();
            table
                .columns('name:name').search($('#search-section :input[name=name]').val())
                .columns('hotel:name').search($('#search-section :input[name=hotel]').val())
                .columns('valid_from:name').search($('#search-section :input[name=valid-from]').val())
                .columns('valid_to:name').search($('#search-section :input[name=valid-to]').val())
                .draw();
        });

        $('.date-picker').datepicker({
            rtl: App.isRTL(),
            orientation: "left",
            autoclose: true,
            format: 'dd.mm.yyyy',
            orientation: "bottom"
        });

        var tableInfoPaxType = $('#modal-info .table-pax-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 2, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableInfoBoardType = $('#modal-info .table-board-type').dataTable({
            "sDom": "t",
            "columnDefs": [
                { 'visible': false, 'targets': [0] }
            ],
            "order": [[ 1, "asc" ]],
            "lengthMenu": [[-1], ["All"]]
        });

        var tableInfoRoomType = $('#modal-info .table-room-type').dataTable({
            "sDom": "t",
            "lengthMenu": [[-1], ["All"]],
            "order": [[ 1, "asc" ]],
            "autoWidth": false,
            "columnDefs": [
                { 'visible': false, 'targets': [0] },
                { 'targets': [1], width: '35%' },
                { 'orderable': false, 'targets': [2] },
                { 'orderable': false, 'targets': [3] },
                { 'orderable': false, 'targets': [4] },
                { 'orderable': false, 'targets': [5] },
                { 'orderable': false, 'targets': [6] }
            ],
            "language": {
                "emptyTable": "No room type selected"
            }
        });

        $('.hotel-category').barrating({
            theme: 'fontawesome-stars',
            readonly: true
        });

        $('#table tbody').on( 'click', '.dt-view', function (e) {
            var data = table.row( $(this).parents('tr') ).data();
            var contract = data['contract'];
            var hotelContract = contract.hotel_contract;
            var hotel = hotelContract.hotel;
            var paxTypes = hotelContract.pax_types;
            var boardTypes = hotelContract.board_types;
            var roomTypes = hotelContract.room_types;
            var country = hotel.country != null ? hotel.country.name : '';
            var state = hotel.state != null ? hotel.state.name : '';
            var city = hotel.city != null ? hotel.city.name : '';
            var hotelChain = hotel.hotel_chain != null ? hotel.hotel_chain.name : '';

            $('#modal-info :input[name=name]').val(contract.name);
            $('#modal-info :input[name=contract]').val(hotelContract.name);
            $('#modal-info :input[name=valid-from]').val(moment(hotelContract.valid_from, 'YYYY-MM-DD').format('DD.MM.YYYY'));
            $('#modal-info :input[name=valid-to]').val(moment(hotelContract.valid_to, 'YYYY-MM-DD').format('DD.MM.YYYY'));
            $('#modal-info :input[name=hotel]').val(hotel.name);
            $('#modal-info :input[name=country-text]').val(country);
            $('#modal-info :input[name=state-text]').val(state);
            $('#modal-info :input[name=city-text]').val(city);
            $('#modal-info :input[name=postal-code]').val(hotel.postal_code);
            $('#modal-info :input[name=address]').val(hotel.address);
            $('#modal-info :input[name=category]').barrating('set', hotel.category);
            $('#modal-info :input[name=hotel-chain]').val(hotelChain);
            $('#modal-info :input[name=admin-phone]').val(hotel.admin_phone);
            $('#modal-info :input[name=admin-fax]').val(hotel.admin_fax);
            $('#modal-info :input[name=web-site]').val(hotel.web_site);
            $('#modal-info :input[name=turistic-licence]').val(hotel.turistic_licence);
            $('#modal-info :input[name=email]').val(hotel.email);

            tableInfoPaxType.api().clear();
            for (var i = 0; i < paxTypes.length; i++) {
                tableInfoPaxType.api().row.add([
                    paxTypes[i].id,
                    paxTypes[i].code + ': ' + paxTypes[i].name,
                    paxTypes[i].agefrom,
                    paxTypes[i].ageto,
                ]).draw( false );
            }
            tableInfoPaxType.api().columns.adjust().draw();

            tableInfoBoardType.api().clear();
            for (var i = 0; i < boardTypes.length; i++) {
                tableInfoBoardType.api().row.add([
                    boardTypes[i].id,
                    boardTypes[i].code + ': ' + boardTypes[i].name
                ]).draw( false );
            }
            tableInfoBoardType.api().columns.adjust().draw();

            tableInfoRoomType.api().clear();
            for (var i = 0; i < roomTypes.length; i++) {
                tableInfoRoomType.api().row.add([
                    roomTypes[i].id,
                    roomTypes[i].code + ': ' + roomTypes[i].name,
                    roomTypes[i].maxpax,
                    roomTypes[i].minpax,
                    roomTypes[i].minadult,
                    roomTypes[i].minchildren,
                    roomTypes[i].minchildren
                ]).draw( false );
            }
            tableInfoRoomType.api().columns.adjust().draw();
            e.preventDefault();
        });
    });
</script>
@stop