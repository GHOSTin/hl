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
    $('a').on('click', function() {
        if($(this).attr('data-ajax') === 'true'){
            var link = $(this).attr('href');
            location.hash = link;
            return false;
        }
        return true;
    });
    $(window).on('hashchange', function() {
        var link = location.hash.replace('#', '');
        if(link)
            $.get('get_task_content', {
                id: link
            },function(r) {
                init_content(r);
            });
        else
            $('#task_content').find('section').html('');
    }).trigger('hashchange');
});