$(document).ready(function(){
    
    // выводит содержимое счетчика
    $('body').on('click', '.get_meter_content', function(){
        if($(this).siblings().is('.meter-content')){
            $(this).siblings('.meter-content').remove();
        } else{
            $.get('get_meter_content',{
                 id: get_meter_id($(this))
                },function(r){
                    init_content(r);
                });
        }

    // выводит диалог для добавления услуги
    }).on('click', '.get_dialog_add_service', function(){
        $.get('get_dialog_add_service',{
            id: get_meter_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог для исключения услуги
    }).on('click', '.get_dialog_remove_service', function(){
        $.get('get_dialog_remove_service',{
            meter_id: get_meter_id($(this)),
            service_id: $(this).parent().attr('service'),
            },function(r){
                init_content(r);
            });

    // выводит диалог для создания счетчика
    }).on('click', '.get_dialog_create_meter', function(){
        $.get('get_dialog_create_meter',{
            },function(r){
                init_content(r);
            });

    // выводит диалог переименования счетчика
    }).on('click', '.get_dialog_rename_meter', function(){
        $.get('get_dialog_rename_meter',{
            id: get_meter_id($(this))
            },function(r){
                init_content(r);
            });
    });
});

// возвращает идентификатор счетчика
function get_meter_id(obj){
    return obj.closest('.meter').attr('meter');
}