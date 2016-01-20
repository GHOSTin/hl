$(document).ready(function(){

    $('body').on('click', '.get_users', function(){
        $.get('get_users', function(r){
                $('.workspace').html(r);
            });

    // изменяет статус правила
    }).on('click', '.access', function(){
        id = get_user_id($(this));
        access = $(this).attr('value')
        $.get('/users/' + id + '/access/' + access + '/',
        function(r){
            init_content(r);
        });

    // обновляет ограничения
    }).on('click', '.restriction', function(){
        id = get_user_id($(this));
        profile = $(this).parent().attr('type');
        item = $(this).attr('item');
        $.get('/users/' + id + '/restrictions/' + profile + '/' + item + '/',
          function(r){
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

    }).on('click', '.get_access', function(){
      id = get_user_id($(this));
      $.get('/users/' + id +'/access/',
      function(r){
          init_content(r);
      });

    // выводит информацию о пользователе
    }).on('click', '.get_user_information', function(){
        $.get('get_user_information',{
            id: get_user_id($(this))
            },function(r){
                init_content(r);
            });

    }).on('click', '.get_restrictions', function(){
        id = get_user_id($(this));
        $.get('/users/' + id + '/restrictions/',
          function(r){
            init_content(r);
          });

    // выводит диалог создания группы
    }).on('click', '.get_dialog_create_group', function(){
        $.get('get_dialog_create_group', {
            }, function(r){
                init_content(r);
            });

    // выводит диалог создания пользователя
    }).on('click', '.get_dialog_create_user', function(){
        $.get('get_dialog_create_user', {
            }, function(r){
                init_content(r);
            });

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

    // выводит диалог изменения статуса блокировки пользователя
    }).on('click', '.get_dialog_edit_user_status', function(){
        $.get('get_dialog_edit_user_status',{
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
    }).on('click', '.get_groups', function(){
        $.get('get_groups',{
            },function(r){
                $('.workspace').html(r);
            });
    });
    $( ".get_users" ).trigger( "click" );
});

// возвращает идентификатор пользователя
function get_user_id(obj){
    return obj.closest('.user').attr('user');
}

// возвращает идентификатор группы
function get_group_id(obj){
    return obj.closest('.group').attr('group');
}