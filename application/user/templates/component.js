$(document).ready(function(){
    // возвращает содержимое буквы
    $('body').on('click', '.get_user_letter', function(){
        $.get('get_user_letter',{
            letter: $(this).text()
            },function(r){
                init_content(r);
            });

    // выводит соедржимое буквы группы
    }).on('click', '.get_group_letter', function(){
        $.get('get_group_letter',{
            letter: $(this).text()
            },function(r){
                init_content(r);
            });

    // выводит информацию об группе
    }).on('click', '.get_group_content', function(){
        if($(this).siblings().is('.group-content')){
            $(this).siblings('.group-content').remove();
        }else{
            $.get('get_group_content',{
                id: get_group_id($(this))
                },function(r){
                    init_content(r);
                });
        }

    // выводит пользователей группы
    }).on('click', '.get_group_users', function(){
        $.get('get_group_users',{
            id: get_group_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит профиль группы
    }).on('click', '.get_group_profile', function(){
        $.get('get_group_profile',{
            id: get_group_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит информацию об пользователе
    }).on('click', '.get_user_content', function(){
        if($(this).siblings().is('.user-content')){
            $(this).siblings('.user-content').remove();
        }else{
            $.get('get_user_content',{
                id: get_user_id($(this))
                },function(r){
                    init_content(r);
                });
        }

    // выводит диалог добавления пользователя в группу
    }).on('click', '.get_dialog_add_user', function(){
        $.get('get_dialog_add_user',{
            id: get_group_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования названия группы
    }).on('click', '.get_dialog_edit_group_name', function(){
        $.get('get_dialog_edit_group_name',{
            id: get_group_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования ФИО пользователя
    }).on('click', '.get_dialog_edit_fio', function(){
        $.get('get_dialog_edit_fio',{
            id: get_user_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования пароля пользователя
    }).on('click', '.get_dialog_edit_password', function(){
        $.get('get_dialog_edit_password',{
            id: get_user_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования логина пользователя
    }).on('click', '.get_dialog_edit_login', function(){
        $.get('get_dialog_edit_login',{
            id: get_user_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог исключения пользователя из группу
    }).on('click', '.get_dialog_exclude_user', function(){
        $.get('get_dialog_exclude_user',{
            group_id: get_group_id($(this)),
            user_id: get_user_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит буквы групп
    }).on('click', '.get_group_letters', function(){
        $.get('get_group_letters',{
            },function(r){
                init_content(r);
            });

    // выводит буквы пользователей
    }).on('click', '.get_user_letters', function(){
        $.get('get_user_letters',{
            },function(r){
                init_content(r);
            });
    });
});

// возвращает идентификатор пользователя
function get_user_id(obj){
    return obj.closest('.user').attr('user');
}

// возвращает идентификатор группы
function get_group_id(obj){
    return obj.closest('.group').attr('group');
}