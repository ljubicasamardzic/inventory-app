// ajax is used for serial numbers since the modal would otherwise close when an error occurs
// this way ajax keeps the modal open and displays the error messages 

function handleErrorMessages(err_array) {
    for (let i = 0; i < err_array.length; i++) {
        // string split at the blank spaces so that we can get the second element and change it 
        // as well as find the element under which the error message should be displayed

        let splitErr = err_array[i].split(" ");

        // copy the select word since the number at the end of it is used to pinpoint the exact input element we need
        let new_string =  splitErr[1].slice();
        let split = new_string.split(".");
        let el_index = (split[1]);

        // change the error message so that it looks presentable to the user
        splitErr[1] = "serial numbers";

        //let elements = document.getElementsByName('serial_numbers[]');
        let elements = $('input[name="serial_numbers[]"]');
       
        let error_message = document.createElement('p');

        error_message.innerHTML = splitErr.join(" ");
        error_message.setAttribute("style", "color: red;");
        error_message.classList.add('invalid-message');

       // add the message after the input field
       elements[el_index].after(error_message);

       // make the input field with an error visible
       elements[el_index].classList.add("is-invalid");
   }
}

function getInputValues(e) {
    e.preventDefault();

    var values = [];
    $('input[name="serial_numbers[]"]').each(function(){
         values.push($(this).val());
    });

    let equipment_id = $('#equipment_id').val();
    let token = $('input[name="_token"]').val();

    $.ajax({
        'url': '/serial-numbers',
        'type': 'POST',
        'data': {serial_numbers:values, equipment_id:equipment_id, _token:token},
        'success': (res) => {
            window.location.reload();
        },
        'error': (res) => {

        // remove all errors 
        $('.invalid-message').remove();
        $("input.is-invalid").removeClass('is-invalid');

        let errors = res['responseJSON']['errors'];
        let err_array = [];

        // get error messages and push them into an array
        for (let key in errors) {
            err_array.push(errors[key][0]);
        } 

        handleErrorMessages(err_array);
       } 
    });
}

// form for adding serial numbers 
const form = $('#serial_numbers_form');
form.on('submit', getInputValues);

// for deleting serial nums
$('.confirm-delete-btn').on('click', function(e) {
    e.stopPropagation();
    e.preventDefault();
    // getting the id from the button that caused the event to fire
    let id = $(this).attr('data-id');
    console.log($(this), id);
    swal({
        title: 'Are you sure?',
        text: "The action is irreversible.",
        icon: 'warning',
        dangerMode: true,
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
            $("#delete_form_"+ id).submit();
        }
    });
});

$('.clickable-row').click((e) => {
    window.location.href = $(e.currentTarget).data('href');
});

$('.callModal').on('click', function(event) {
    let button = $(this) // Button that triggered the modal
    let id = button.attr('data-id')
    let sn = button.attr('data-sn')
    let form = $('#serial_numbers_edit_form')
    form.attr("action", "/serial-numbers/" + id)
    $('#sn_id').val(id)
    $('#sn_num').val(sn)
});