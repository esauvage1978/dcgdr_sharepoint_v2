
function fillComboboxChained(selecteurSource, selecteurDestination, route, appelEnCascade, selectedId = "") {
    var id = $(selecteurSource).val();

    if (id == null) return;

    $(selecteurDestination).empty();

    $.ajax({
        method: "POST",
        url: route,
        data: { 'id': id, 'enable': 'all' },
        dataType: 'json',
        success: function (json) {
            var selected = '';
            $.each(json, function (index, value) {
                if (selectedId === value.id) {
                    selected = 'selected';
                } else {
                    selected = '';
                }
                $(selecteurDestination).append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
            });
            if (appelEnCascade) {
                $(selecteurDestination).change();
            }
        }
    });
}


function arborescence(fieldSource, route, idUR, appelEnCascade, fieldForm) {
    var data = getVal(fieldForm);

    fieldSource.empty();

    $.ajax({
        method: "POST",
        url: route,
        data: {
            'id': idUR
        },
        dataType: 'json',
        success: function (json) {
            console.log(json);
            var selected = '';
            fieldSource.append('<option value=""></option>');

            $.each(json, function (index, value) {
                if (data === value.id) {
                    selected = 'selected';
                } else {
                    selected = '';
                } fieldSource.append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option > ');
            }
            );
            if (appelEnCascade) {
                fieldSource.change();

            }
        }

    }
    );
}

function arborescenceChained(fieldSource, route, idUR, dataSource, appelEnCascade, fieldForm) {
    var data = fieldForm.val();

    fieldSource.empty();
    $.ajax({
        method: "POST",
        url: route,
        data: {
            'id': idUR,
            'data': dataSource
        },
        dataType: 'json',
        success: function (json) {
            var selected = '';
            fieldSource.append('<option  value=""></option>');
            $.each(json, function (index, value) {
                if (data === value.id) {
                    selected = 'selected';
                } else {
                    selected = '';
                }
                fieldSource.append('<option ' + selected + ' value="' + value.id + '">' + value.name + '</option>');
            });
            if (appelEnCascade) {
                fieldSource.change();
            }
        }
    });
}


function arbo_grouping(fieldSource, route, idmprocess, fieldForm) {

    var data = getVal(fieldForm);

    fieldSource.empty();

    $.ajax({
        method: "POST",
        url: route,
        data: {
            'mprocess': idmprocess
        },
        dataType: 'json',
        success: function (json) {
            var selected = '';
            fieldSource.append('<option value=""></option>');
            $.each(json.value, function (index, value) {
                if (data === value.grouping) {

                    selected = 'selected';
                } else {
                    selected = '';
                }
                fieldSource.append('<option ' + selected + ' value="' + value.grouping + '">' + value.grouping + '</option>');

            });
        }
    });
}


