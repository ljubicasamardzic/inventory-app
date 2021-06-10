function confirmUserDelete(user_id, event){
    event.stopPropagation();
    if(confirm('Da li ste sigurni?')){
        console.log("#delete_form_"+user_id);
        $("#delete_form_"+user_id).submit();
    }
}
$('.clickable-row').click((e) => {
    window.location.href = $(e.currentTarget).data('href');
});
