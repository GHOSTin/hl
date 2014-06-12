$(document).ready(function(){
    $('#get_active_tasks').on('click', function(){
        $.get('show_active_tasks', {
        },function(r){
            init_content(r);
        });
    }).trigger("click");
    $(document).on('click', '.get_dialog_create_task', function(){
        $.get('get_dialog_create_task',{
        },function(r){
            init_content(r);
        });
    });
});