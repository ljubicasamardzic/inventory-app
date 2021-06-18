function availableSerialNums() {

    let equipment_id = $("#equipment_select").val();
    if(equipment_id == ''){
        $("#serial_number_select").html('');
        return;
    }

    $.ajax({
       'url' : '/equipment-serial-numbers/' + equipment_id,
       'type' : 'GET',
       'success': (response) => {
        let serial_numbers = response;
        let options = '';
        serial_numbers.forEach((number) => {
            let selected = '';
            // if(equipment_id && equipment_id == number.equipment_id) selected = 'selected';
            options += `<option value=\"${number.id}\">${number.serial_number}</option>`;
        });
        $("#serial_number_select").html(options);
       }
    });
}

$('#take_over_button').on('click', function() {
    // e.stopPropagation();
    // e.preventDefault();
    swal({
        title: 'Are you sure?',
        icon: 'warning',
        buttons: {
            cancel: {
            text: "Cancel",
            value: null,
            visible: true,
            className: "",
            closeModal: true,
          },
          confirm: {
            text: "OK",
            value: true,
            visible: true,
            className: "",
            closeModal: true
            }},
        }).then((value) => {
            if (value) {
                $('#update1-form').submit();
        }
    });
});
