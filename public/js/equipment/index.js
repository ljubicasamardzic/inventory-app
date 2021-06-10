function confirmEquipmentDelete(equipment_id, event){
    event.stopPropagation();
    if(confirm('Da li ste sigurni?')){
        console.log("#delete_form_"+equipment_id);
        $("#delete_form_"+equipment_id).submit();
    }
    return;
}
$('.clickable-row').click((e) => {
    window.location.href = $(e.currentTarget).data('href');
});
