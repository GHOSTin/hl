$(document).ready(function(){
    $('body').on('click', '.get_dialog_import_numbers', function(){
        $.get('get_dialog_import_numbers',{
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.get_dialog_import_meters', function(){
        $.get('get_dialog_import_meters',{
            },function(r){
                init_content(r);
            });
    });
});