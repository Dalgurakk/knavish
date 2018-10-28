function cleanLocationSelected() {
    $('#node-data :input[name=name]').val('');
    $('#node-data :input[name=code]').val('');
    $('#node-data :input[name=active]').prop('checked', '');
    $('#node-data :input[name=active]').val(0);
    $('#node-data').find('div[name=icon] > i').attr('class', 'glyphicon glyphicon-question-sign');
    $('.edit-node').addClass('disabled');
    $('.delete-node').addClass('disabled');
}

$(document).ready(function () {
    var selectedNode = '0';
    var needUpdate = false;
    var porletMaxHeight = $('.porlet-data').css('height');
    $('.porlet-tree').css('min-height', porletMaxHeight);

    $('.expand').on('click', function() {
        var expanded = $('#tree-container').attr('data-expanded');
        if (expanded == 'true') {
            $( "#tree-container" ).animate({
                width: "66.66%"
            }, 500, function() {
                $( "#node-data" ).fadeIn("slow");
            });
            $('#tree-container').attr('data-expanded', 'false');
        }
        else {
            $( "#tree-container" ).animate({
                width: "100%"
            }, 500);
            $( "#node-data" ).css('display', 'none');
            $('#tree-container').attr('data-expanded', 'true');
        }
    });

    function deleteNode() {
        swal({
            title: 'Confirmation',
            text: 'Are you sure you want to delete the location: "' + $('#node-data :input[name=name]').val() + '"?',
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
                if (selectedNode == '0') {
                    toastr['error']("Please select the node you want to delete.", "Error");
                }
                else {
                    $.ajax({
                        url: routeDelete,
                        "type": "POST",
                        "data":  {
                            id: selectedNode
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
                                    selectedNode = '0';
                                    toastr['success'](response.message, "Success");
                                    $('#tree').jstree("refresh");
                                    cleanLocationSelected();
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
    }

    function showAddNode() {
        formAdd.validate().resetForm();
        formAdd[0].reset();
        $('#modal-add .icon-picker').iconpicker('setIcon', 'glyphicon-question-sign');
        $('#modal-add :input[name=parent-id]').val(selectedNode);
        $('#modal-add').modal('show');
    }

    function showEditNode() {
        var icon = $('#node-data').find('div[name=icon] > i').attr('class');
        var arr = icon.split(" ");
        var active = $('#node-data :input[name=active]').prop('checked') ? 1 : 0;

        $('#modal-edit :input[name=id]').val(selectedNode);
        $('#modal-edit :input[name=name]').val($('#node-data :input[name=name]').val());
        $('#modal-edit :input[name=code]').val($('#node-data :input[name=code]').val());
        $('#modal-edit :input[name=icon]').val(arr[1]);
        $('#modal-edit .icon-picker').iconpicker('setIcon', arr[1]);
        if (active == 1) {
            $('#modal-edit :input[name=active]').prop('checked', 'checked');
            $('#modal-edit :input[name=active]').val(1);
        }
        else {
            $('#modal-edit :input[name=active]').prop('checked', '');
            $('#modal-edit :input[name=active]').val(0);
        }
        $('#modal-edit').modal('show');
    }

    $('#modal-add .icon-picker').iconpicker({
        placement: 'bottom',
        icon: 'glyphicon-globe'
    });

    $('#modal-edit .icon-picker').iconpicker({
        placement: 'bottom',
        icon: 'glyphicon-globe'
    });

    $('#modal-add .icon-picker').on('change', function(e) {
        $('#modal-add :input[name=icon]').val(e.icon);
    });

    $('#modal-edit .icon-picker').on('change', function(e) {
        $('#modal-edit :input[name=icon]').val(e.icon);
    });

    $('#tree')
        .on("changed.jstree", function (e, data) {
            if(data.selected.length) {
                var obj = data.instance.get_node(data.selected[0]).data;
                selectedNode = obj.id;
                $('#node-data :input[name=obj]').val(obj);
                $('#node-data :input[name=name]').val(obj.name);
                $('#node-data :input[name=code]').val(obj.code);
                $('#node-data').find('div[name=icon] > i').attr('class', obj.icon);
                if (obj.active == 1) {
                    $('#node-data :input[name=active]').prop('checked', 'checked');
                    $('#node-data :input[name=active]').val(1);
                }
                else {
                    $('#node-data :input[name=active]').prop('checked', '');
                    $('#node-data :input[name=active]').val(0);
                }
                $('.edit-node').removeClass('disabled');
                $('.delete-node').removeClass('disabled');
            }
        })
        .jstree({
            'core' : {
                'data' : {
                    "type": "POST",
                    "dataType": "json",
                    "url": routeRead,
                    "data" : function (node) {
                        return { 'id' : node.id };
                    }
                }
            },
            contextmenu : {
                items : {
                    remove : false,
                    add : {
                        label	: "Add",
                        icon	: "fa fa-plus",
                        action	: function (NODE, TREE_OBJ) {
                            showAddNode();
                        },
                        separator_before : true,
                        separator_after : true
                    },
                    edit : {
                        label	: "Edit",
                        icon	: "fa fa-pencil",
                        action	: function (NODE, TREE_OBJ) {
                            showEditNode();
                        },
                        separator_before : true,
                        separator_after : true
                    },
                    delete : {
                        label	: "Delete",
                        icon	: "fa fa-trash",
                        action	: function (NODE, TREE_OBJ) {
                            deleteNode();
                        },
                        separator_before : true,
                        separator_after : true
                    }
                }
            },
            "plugins" : [
                //"checkbox",
                //"dnd",
                //"massload",
                //"search",
                //"sort",
                //"state",
                "contextmenu",
                //"types",
                //"unique",
                //"wholerow",
                //"changed",
                //"conditionalselect"
            ]
        })
    ;

    $('.node-option').on('click', function (e) {
        var value = $(this).attr('data');
        if (value == 'root') {
            $('#modal-add :input[name=parent-id]').val('0');
            $('#modal-add').modal('show');
        }
        else if (value == 'node') {
            if (selectedNode == '0') {
                toastr['error']("Please select a parent.", "Error");
            }
            else {
                showAddNode();
            }
        }
        e.preventDefault();
    });

    $('.edit-node').on('click', function (e) {
        if (selectedNode != '0') {
            showEditNode();
        }
        e.preventDefault();
    });

    $('.delete-node').click(function(){
        if (selectedNode != '0') {
            deleteNode();
        }
    });

    var formAdd = $('#form-add');
    formAdd.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            name: {
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
                "url": routeCreate,
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
                            needUpdate = true;
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

    var formEdit = $('#form-edit');
    formEdit.validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        rules: {
            name: {
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
                "url": routeUpdate,
                "type": "POST",
                "data": formEdit.serialize(),
                "beforeSend": function() {
                    App.showMask(true, formEdit);
                },
                "complete": function(xhr, textStatus) {
                    App.showMask(false, formEdit);
                    if (xhr.status != '200') {
                        toastr['error']("Please check your connection and try again.", "Error on loading the content");
                    }
                    else {
                        var response = $.parseJSON(xhr.responseText);
                        if (response.status == 'success') {
                            needUpdate = true;
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
            $('#tree').jstree("refresh");
            needUpdate = false;
        }
    });

    $('.mt-checkbox').change(function () {
        var checkbox = $('.mt-checkbox > input[type=checkbox]');
        if (checkbox.is(':checked'))
            $('.mt-checkbox > input[type=checkbox]').val(1);
        else
            $('.mt-checkbox > input[type=checkbox]').val(0);
    });

    $('.add').on('click', function () {
        formAdd.validate().resetForm();
        formAdd[0].reset();
    });

    $('.excel').on('click',function(){
        var url = routeExcel;
        window.location = url;
    });
});