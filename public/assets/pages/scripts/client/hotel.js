$(document).ready(function () {
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
        "iDisplayLength" : 25,
        "ajax": {
            "url": routeRead,
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
            { data: 'hotel', name: 'hotel', orderable: false },
            { data: 'valid_from', name: 'valid_from', orderable: false },
            { data: 'valid_to', name: 'valid_to', orderable: false },
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
                    var data =
                        '<form method="get" action="' + routeSetting + '">' +
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
        "sDom": "tip",
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
            { 'orderable': false, 'targets': [6] },
            { 'orderable': false, 'targets': [7] },
            { 'orderable': false, 'targets': [8] }
        ],
        "pageLength": 10
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
                roomTypes[i].max_pax,
                roomTypes[i].max_adult,
                roomTypes[i].min_adult,
                roomTypes[i].max_children,
                roomTypes[i].min_children,
                roomTypes[i].max_infant,
                roomTypes[i].min_infant
            ]).draw( false );
        }
        tableInfoRoomType.api().columns.adjust().draw();
        e.preventDefault();
    });

    $('.excel').on('click',function(){
        var query = {
            name: $('#search-section :input[name=name]').val(),
            hotel: $('#search-section :input[name=hotel]').val(),
            validFrom: $('#search-section :input[name=valid-from]').val(),
            validTo: $('#search-section :input[name=valid-to]').val(),
            active: $('#search-section :input[name=active]').val()
        };
        var url = routeExcel + "?" + $.param(query);
        window.location = url;
    });
});
