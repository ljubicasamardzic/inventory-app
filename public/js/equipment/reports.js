$(document).ready(function() {

    let selects = ['department_multiple_slt', 'positions_multiple_slt', 'categories_multiple_slt', 'employees_multiple_slt', 'equipment_multiple_slt'];
    selects.forEach(select => {
        $('.' + select).select2({
            placeholder: 'Select an option',
            theme: "classic"
        });
    });

});