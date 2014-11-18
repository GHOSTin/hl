$(document).ready(function(){
    $('body').on('click', '.get_dialog_export_numbers', function(){
        $.get('get_dialog_export_numbers',{
        },function(r){
            init_content(r);
        });
    }).on('click', '.export_numbers', function(){
        $.get('export_numbers', {
        },function(r){
            init_content(r);
        });
    });
});
