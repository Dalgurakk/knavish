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
        //"sDom": "lftip",
        "sDom": "ltip",
        "iDisplayLength" : 25,
        "ajax": {
            "url": routeRead,
            "type": "POST",
            "data": {
                "from": $('#search-from').val(),
                "to": $('#search-to').val()
            },
            "complete": function(xhr, textStatus) {
                if (xhr.status != '200') {
                    toastr['error']("Please check your connection and try again.", "Error on loading the content");
                }
            }
        },
        "order": [[ 12, "desc" ]],
        columns: [
            {data: 'id', name: 'id', visible: false},
            {data: 'user_type', name: 'user_type', visible: false},
            {data: 'user_id', name: 'user_id'},
            {data: 'user_username', name: 'user_username'},
            {
                targets: 'user_email',
                name: 'user_email',
                "data": function ( row, type, val, meta ) {
                    var data = '<a href="mailto:' + row.user_email + '">' + row.user_email + '</a>';
                    return data;
                },
                visible: false
            },
            {data: 'user_name', name: 'user_name', visible: false},
            {data: 'event', name: 'event'},
            {data: 'auditable_type', name: 'auditable_type'},
            {data: 'auditable_id', name: 'auditable_id'},
            {data: 'url', name: 'url'},
            {data: 'ip_address', name: 'ip_address'},
            {data: 'user_agent', name: 'user_agent', visible: false},
            {
                targets: 'created_at',
                name: 'created_at',
                "data": function ( row, type, val, meta ) {
                    //var data = row.created_at.date.substr(0, 19);
                    var data = row.created_at;
                    return data;
                }
            },
            {
                targets: 'details',
                orderable: false,
                name: 'actions',
                "className": "dt-center",
                "data": function ( row, type, val, meta ) {
                    var data = '<div class="dt-actions">' +
                        '<a class="btn btn-default btn-circle btn-icon-only btn-action dt-view" data-toggle="modal" href="#modal-info">' +
                        '<i class="glyphicon glyphicon-eye-open btn-action-icon"></i></a>'+
                        '</div>';
                    return data;
                },
                visible: false
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
        $('#search-section :input[name=user-id]').val('');
        $('#search-section :input[name=username]').val('');
        $('#search-section :input[name=email]').val('');
        $('#search-section :input[name=name]').val('');
        $('#search-section :input[name=event]').val('');
        $('#search-section :input[name=auditable-type]').val('');
        $('#search-section :input[name=auditable-id]').val('');
        $('#search-section :input[name=url]').val('');
        $('#search-section :input[name=user-agent]').val('');
        $('#search-section :input[name=ip-address]').val('');
        $('#search-section :input[name=from]').val('');
        $('#search-section :input[name=to]').val('');
    });

    $('.btn-search-cancel').on('click', function (e) {
        e.preventDefault();
        $('#search-section').slideToggle();
    });

    $('.btn-search-submit').on( 'click', function (e) {
        e.preventDefault();
        //var role = $('#search-section :input[name=role]').val() != "" ? $('#search-section :input[name=role] option:selected').text() : '';
        //alert($('#search-section :input[name=to]').val());
        table
            .columns('id:name').search($('#search-section :input[name=user-id]').val())
            .columns('user_username:name').search($('#search-section :input[name=username]').val())
            .columns('user_email:name').search($('#search-section :input[name=email]').val())
            .columns('user_name:name').search($('#search-section :input[name=name]').val())
            .columns('event:name').search($('#search-section :input[name=event]').val())
            .columns('auditable_type:name').search($('#search-section :input[name=auditable-type]').val())
            .columns('auditable_id:name').search($('#search-section :input[name=auditable-id]').val())
            .columns('url:name').search($('#search-section :input[name=url]').val())
            .columns('ip_address:name').search($('#search-section :input[name=ip-address]').val())
            .columns('user_agent:name').search($('#search-section :input[name=user-agent]').val())
            /*.columns('auditable_id:name').search($('#search-section :input[name=from]').val())
            .columns('auditable_id:name').search($('#search-section :input[name=to]').val())*/
            .draw();
    });

    $('#table tbody').on( 'click', '.dt-view', function (e) {
        e.preventDefault();
    });

    $('.cancel-form').on('click', function(e) {
        if(needUpdate) {
            table.draw();
            needUpdate = false;
        }
    });

    $('.excel').on('click',function(){
        var query = {
            username: $('#search-section :input[name=username]').val(),
            role: $('#search-section :input[name=role]').val(),
            name: $('#search-section :input[name=name]').val(),
            email: $('#search-section :input[name=email]').val(),
            active: $('#search-section :input[name=active]').val()
        };
        var url = routeExcel + "?" + $.param(query);
        window.location = url;
    });
});