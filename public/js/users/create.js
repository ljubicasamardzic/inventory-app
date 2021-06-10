
function fillPositions(position_id = null){
    let department_id = $("#department_select").val();
    if(department_id == ''){
        $("#position_select").html('');
        return;
    }
    $.ajax({
       'url' : '/positions-by-department/'+department_id,
       'type' : 'GET',
       'success': (response) => {
           let positions = response;
           let options = '';
           positions.forEach((position) => {
               let selected = '';
               if(position_id && position_id == position.id) selected = 'selected';
               options += `<option value=\"${position.id}\" ${selected}>${position.name}</option>`;
           });
           $("#position_select").html(options);
       }
    });
}
