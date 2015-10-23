$(document).ready(function(){

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

    }).on('click', '.get_outage_reports', function(){
        $.get('/reports/outages/',{
            },function(r){
                init_content(r);
            });
    });
});