jQuery(function () {

    function showAndHide(divToShow, divToHide, close=false) {
        
        if (close == false) {
            divToHide.addClass('d-none')
            divToHide.removeClass('d-block')
    
            divToShow.addClass('d-block')
            divToShow.removeClass('d-none')
        } else if (close == true) {
            divToHide.addClass('d-none')
            divToHide.removeClass('d-block')

            divToShow.addClass('d-none')
            divToShow.removeClass('d-block')
        }

    }
    
    let select = $('#ticket_request_type_id')
    let supplies_div = $('#supplies_div')
    let equipment_div =  $('#equipment_div')
    let equipment_cat = $('#equipment_category_id')
    let equipment_desc = $('#equipment_desc')
    let supplies = $('#supplies_desc')
    let quantity = $('#supplies_quantity')

    function emptyFields() {
        equipment_cat.val('')
        equipment_desc.val('')
        supplies.val('')
        quantity.val('')
    }

    select.on('change', () => {
       if (select.val() == '1') {
           showAndHide(equipment_div, supplies_div)
           emptyFields()
       } else if (select.val() == '2') {
          showAndHide(supplies_div, equipment_div)
          emptyFields()
       } else if (select.val() == '') {
            showAndHide(equipment_div, supplies_div, true)
            emptyFields()
       }
    });
});

