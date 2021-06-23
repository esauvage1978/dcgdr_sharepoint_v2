
function fillComboboxRubricChained(selecteurSource, selecteurDestination, route, appelEnCascade, addReference, selectedId="") {
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
            var tp='';
            var tc='';
            $.each(json, function (index, value) {

                tc=value.thematic;

                if(tp !== tc) {
                    if(tp!=='') {
                        $(selecteurDestination).append('</optgroup>');
                    }
                    $(selecteurDestination).append('<optgroup label="'+ tc +'">');
                }

                if(selectedId === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteurDestination).append('<option ' + selected + ' value="' + value.id + '">' +
                    (addReference ? value.ref + ' - ' : '')
                    + value.name + '</option>');
                tp=tc;
            });
            $(selecteurDestination).append('</optgroup>');
            if (appelEnCascade) {
                $(selecteurDestination).change();
            }
        }
    });
}

function fillComboboxRubric(selecteur, route, appelEnCascade, selectedId="") {
    $(selecteur).empty();
    $.ajax({
        method: "POST",
        url: route,
        data: {'enable': 'all'},
        dataType: 'json',
        success: function (json) {
            var selected='';
            var tp='';
            var tc='';
            $.each(json, function (index, value) {
                tc=value.thematic;

                if(tp !== tc) {
                    if(tp!=='') {
                        $(selecteur).append('</optgroup>');
                    }
                    $(selecteur).append('<optgroup label="'+ tc+'">');
                }

                if(selectedId === value.id ) {
                    selected='selected';
                } else {
                    selected='';
                }
                $(selecteur).append('<option ' + selected + ' value="' + value.id + '">'
                    + value.name + '</option>');

                tp=tc;
            });
            $(selecteur).append('</optgroup>');
            if (appelEnCascade) {
                $(selecteur).change();
            }
        }
    });
}

