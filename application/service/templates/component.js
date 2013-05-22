$(document).ready(function(){
    
    $('body').on('click', '.get_service_content', function(){
        if($(this).siblings().is('.houses')){
            $(this).siblings('.houses').remove();
        } else{
            $.get('get_street_content',{
                 id: $(this).parent().attr('street')
                },function(r){
                    init_content(r);
                });
        }
    // диалог для создания услуги
    }).on('click', '.get_dialog_create_service', function(){
        $.get('get_dialog_create_service',{
            },function(r){
                init_content(r);
            });
    });
});
function get_service_id(obj){
    return obj.closest('.service').attr('service');
}