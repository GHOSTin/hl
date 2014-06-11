$(document).ready(function(){
    $(document).on('click', '.get_dialog_create_task', function(){
        $.get('get_dialog_create_task',{
        },function(r){
            init_content(r);
        });
    })
});