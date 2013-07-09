$(document).ready(function(){
    // возвращает диалог создания процессингового центра
    $('body').on('click', '.get_dialog_create_processing_center', function(){
        $.get('get_dialog_create_processing_center',{
            },function(r){
                init_content(r);
            });

    // выводит информацию о процессинговом центре
    }).on('click', '.get_processing_center_content', function(){
        if($(this).siblings().is('.processing-center-content')){
            $(this).siblings('.processing-center-content').remove();
        }else{
            $.get('get_processing_center_content',{
                id: get_processing_center_id($(this))
                },function(r){
                    init_content(r);
                });
        }
    });
});

// возвращает идентификатор процессингового центра
function get_processing_center_id(obj){
    return obj.closest('.processing-center').attr('processing-center');
}