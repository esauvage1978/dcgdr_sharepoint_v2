
function fillComboboxChained(selecteurSource, selecteurDestination, route, appelEnCascade, addReference, selectedId="") {
        var id = $(selecteurSource).val();
    if (id == null) return;

    $(selecteurDestination).empty();

    $.ajax({
        method: "POST",
        url: route,
        data: {'id': id, 'enable': 'all'},
        dataType: 'json',
        success: function (json) {
            var selected='';
            $.each(json, function (index, value) {
                if(selectedId === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteurDestination).append('<option ' + selected + ' value="' + value.id + '">' +
                    (addReference ? value.ref + ' - ' : '')
                    + value.name + '</option>');
            });
            if (appelEnCascade) {
                $(selecteurDestination).change();
            }
        }
    });
}

function arborescence(selecteur, route, underRubricId, appelEnCascade, dataSelecteur) {
    var data = $(dataSelecteur).val();

    $(selecteur).empty();
    $.ajax({
        method: "POST",
        url: route,
        data: {'id': underRubricId},
        dataType: 'json',
        success: function (json) {
            var selected='';
            $(selecteur).append('<option  value=""></option>');
            $.each(json, function (index, value) {
                if(data === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteur).append('<option ' + selected + ' value="' + value.id + '">'
                    + value.name + '</option>');
            });
            if (appelEnCascade) {
                $(selecteur).change();
            }
        }
    });
}
function arborescenceChained(selecteur, route, underRubricId, appelEnCascade, dataSelecteur,selectedValue ) {
    var data = $(dataSelecteur).val();

    $(selecteur).empty();
    $.ajax({
        method: "POST",
        url: route,
        data: {'id': underRubricId, 'data':selectedValue},
        dataType: 'json',
        success: function (json) {
            var selected='';
            $(selecteur).append('<option  value=""></option>');
            $.each(json, function (index, value) {
                if(data === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteur).append('<option ' + selected + ' value="' + value.id + '">'
                    + value.name + '</option>');
            });
            if (appelEnCascade) {
                $(selecteur).change();
            }
        }
    });
}

