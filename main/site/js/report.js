$(document).ready(function(){
    // возвращает список отчетов по заявкам и фильтр
    $('body').on('click', '.get_query_reports', function(){
        $.get('/reports/queries/',{
            },function(r){
                init_content(r);
            });
    }).on('click', '.get_event_reports', function(){
        $.get('/reports/event/',{
            },function(r){
                init_content(r);
            });
    });
});