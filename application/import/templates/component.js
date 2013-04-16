$(document).ready(function(){
    $('body').on('click', '.get_dialog_import_numbers', function(){
        $.get('get_dialog_import_numbers',{
            },function(r){
                init_content(r);
            });
    }); 
});