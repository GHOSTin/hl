$(document).ready(function(){
    $('#get_active_tasks').on('click', function(){
        window.location.hash = "#";
        $.get('show_active_tasks', {
        },function(r){
            init_content(r);
        });
    }).trigger("click");
    $('#get_finished_tasks').on('click', function(){
        window.location.hash = "#";
        $.get('show_finished_tasks', {
        },function(r){
            init_content(r);
        });
    });
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
        var link = location.hash.replace('#', '').replace('/', '');
        if(link) {
            $('#task_container').addClass('hidden-xs');
            $('#task_content').addClass('show').removeClass('hidden-xs');
            $.get('get_task_content', {
                id: link
            },function(r) {
                init_content(r);
            });
        }
        else {
            $('#task_container').addClass('show').removeClass('hidden-xs');
            $('#task_content').addClass('hidden-xs').find('section').html('');
        }
    }).trigger('hashchange');
});