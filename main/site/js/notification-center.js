/**
 * выводит в лог переданное значение
 * @function
 * @param {*} param
 */
var log = function (param) {
    console.log(param);
};
/**
 * Декодирует строку в ASCII символы
 * @param {string} s строка для декодирования
 * @returns {string}
 */
function decode(s) {
    var pluses = /\+/g;
    return decodeURIComponent(s.replace(pluses, ' '));
}
/**
 * Удаляет из строки лишние спецсимволы
 * @param {string} s
 * @returns {string}
 */
function converted(s) {
    if (s.indexOf('"') === 0) {
        s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
    }
    try {
        return s;
    } catch(er) {}
}
/**
 * Возвращает значение запрашиваемой куки
 * @param {string} key имя куки
 * @returns {string|null} значение, если отсутствует то возвращает нуль
 */
var get_cookie = function(key) {
    var cookies = document.cookie.split('; ');
    var result = key ? undefined : {};
    for (var i= 0, l = cookies.length; i < l; i++) {
        var parts = cookies[i].split('=');
        var name = decode(parts.shift());
        var cookie = decode(parts.join('='));

        if(key && key === name) {
            result = converted(cookie);
            break;
        }

        if(!key) {
            result[name] = converted(cookie);
        }
    }
    return result;
}
/**
 * функция аналог jQuery:contains без учета регистра
 */
$.extend($.expr[':'], {
    'containsi': function(elem, i, match, array) {
        return (elem.textContent || elem.innerText || '').toLowerCase()
            .indexOf((match[3] || "").toLowerCase()) >= 0;
    }
});
var uid, host, port;
$.getJSON('/api/get_chat_options', {async: false}).done(function(data){
    uid = parseInt(data.user);
    host = data.host;
    port = data.port;
});
/** тайтл страницы */
var global_title;
/** сокет-соединение для центра уведомлений */
var notify_center = io.connect('http://'+ get_cookie('chat_host') + ':' + get_cookie('chat_port') + '/notify');
/** сокет-соединение для чата */
var chat = io.connect('http://'+ get_cookie('chat_host') + ':' + get_cookie('chat_port') + '/chat');
/**
 * если сокет создан, но не произошло соединение, то совершать пересоединение
 * @function
 */
var tryReconnect = function(){
    if (notify_center.socket.connected === false &&
         notify_center.socket.connecting === false) {
         notify_center.socket.connect();
         chat.socket.connect();
    }
}
/**
 * с интервалом раз в минуту вызывать функцию tryReconnect
 * @function
 * @type {number}
 */
var intervalID = setInterval(tryReconnect, 60000);
/**
 * обработка события центра уведомлений "если произошло соединение"
 */
notify_center.on('connect', function(){
    if($('.current_user').length){
        global_title = $(document).attr('title') || ''
        notify_center.json.send({"type":"user_ready", "data":{'uid': uid}});
        chat.json.send({"type":"user_ready", "data":{'uid': uid}});
    }
    clearInterval(intervalID);
});
/**
 * обработка события центра уведомлений "если пришло сообщение"
 */
notify_center.on('message', function(event){
    var message = event.data;
    switch (message.type) {
        case 'toggle_semaphore':
            if(!$('#_hidden_notification_center').length || $('#_hidden_notification_center').is(':hidden')){
                $('.notification-center-icon').addClass('icon-white');
            }
            break;
        case 'unread_count_messages':
            if(message.data>0){
                $(document).attr('title', message.data+' новых сообщений');
            } else {
                $(document).attr('title', global_title);
                $('.notification-center-icon').removeClass('icon-white');
            }
            break;
        default:
            console.log('received unknown message:');
            console.log(message);
    }
});
/**
 * обработка события центра уведомлений "если соединение было разорвано"
 */
notify_center.on('disconnect', function(){
    intervalID = setInterval(tryReconnect, 60000);
});
/**
 * обработка события чата "если пришло сообщение"
 */
chat.on('message', function (event) {
    var message = event.data;
    switch (message.type) {
        case 'previous_messages': /** сообщение - присланы предыдущие сообщения */
            userList.list[message.data.uid].loadPreviousMessages(message.data.messages, message.data.status || 'new');
            break;
        case 'updates': /** сообщение - произвести обновления */
            feed.process(message.data);
            break;
        case 'history': /** сообщение - загрузка истории сообщений */
            userList.list[message.data.uid].history_dates[message.data.date].loadHistory(message.data.messages);
            break;
        default: /** неизвестный тип сообщения */
            log('received unknown message:');
            log(message);
    }
});
/**
 * произведен клик на имя пользователя
 */
$(document).on('click', '.chat-users li a', function (e) {
    e.preventDefault();
    var user = userList.list[$(this).attr("user_id")];
    /** если у пользователя не были подгружены последние 15 сообщений*/
    if (!user.previousMessagesLoaded) {
        user.loadPreviousMessages();
    }
    user.markAllAsRead();
    $("textarea").val("");
    $('.chat.active .chat-feed').mCustomScrollbar("update");
    $('.chat.active .chat-feed').mCustomScrollbar("scrollTo", "bottom");
});
/**
 * произведена отправка формы
 */
$(document).on('submit', 'form.message', function (e) {
    var form = $(e.target);
    var files = [];
    $('.attachments li').each(function(){
        files.push($(this).attr('id'));
    });
    if(form[0].message.value !== '' || $('.attachments li').length) {
        chat.json.send({'type':'send_message', "data":{'to':$('.chat.active').attr('id').split('_')[1], 'message':form[0].message.value.replace(/\r\n|\r|\n/g, "<br>"), 'attachments': files.join(',')}});
    }
    $("input[id=lefile]").attr({ value: '' });
    $(".attachments").html("");
    form[0].message.value = "";
    return false;
});
/**
 * обработка события нажатия клавиш Ctrl+Enter
 */
$(document).on('keypress', '.chat.active textarea',function (event) {
    var keyCode = (event.which ? event.which : event.keyCode);

    if (keyCode === 10 || keyCode == 13 && event.ctrlKey) {
        $('.chat.active').find('form.message').submit();
    }
});
/**
 * обработка события нажатия на кнопку показа истории
 */
$(document).on('click', '.chat-history', function(e){
    e.preventDefault();
    /** если клавиша не была нажата, то подгрузить даты */
    if($(this).hasClass('active')){
        var user = userList.list[$('.chat.active').attr('id').split('_')[1]];
        chat.json.send({'type':'get_history_list', 'data':{"uid": user.id, "offset": Object.keys(user.history_dates).length}});
    }
    $('.chat.active ul#chat-dates').toggle(10, function(){
        $('.chat.active .chat-feed').mCustomScrollbar("update");
    });
    $('.chat.active ul#chat-history').toggle(10, function(){
        $('.chat.active .chat-feed').mCustomScrollbar("update");
        $('.chat.active .chat-feed').mCustomScrollbar("scrollTo", "bottom");
    });
});
/**
 * обработка события нажатия на конкретную дату в истории
 */
$(document).on('click', '.chat.active ul#chat-history header', function(e){
    e.preventDefault();
    if($(this).siblings('ul').is(':hidden')){
        var user = userList.list[$('.chat.active').attr('id').split('_')[1]];
        var date = user.history_dates[$(this).attr('id')];
        var d = new Date();
        if(!date.history_loaded || date.id === new Date(d.getFullYear(), d.getMonth(), d.getDate(), 12).getTime()){
            date.loadHistory();
        }
    }
    $(this).siblings('ul').toggle(10, function(){
        $(".chat.active .chat-feed").mCustomScrollbar("update");
    });
});

$(document).on('click', '#chat-user-filter-clear', function(e){
    e.preventDefault();
    $("#chat-user-filter").val("").focus();
    userList.renderMenu();
});
/**
 * обработка события нажатия на кнопку центра уведомлений
 */
$('#nt-center').on('click', function(e){
    e.preventDefault();
    /** если существует то показать/скрыть */
    if($('#_hidden_notification_center').length){
        $('#_hidden_notification_center').fadeToggle(10,function(){
            $(".chat-user-list").mCustomScrollbar("update");
            $('.chat-users li.active').removeClass('active');
            $('.chat.active').removeClass('active');
        });
    } else {
        /** подгрузить контент */
        $.get('/profile/get_notification_center_content',{
        },function(r){
            /** создать блок с полученным контентом */
            create_notification_center($('#nt-center').parent(), r);
            build_chat_window();
        });
    }
});

var build_chat_window = function(){
    var users = [];
    $('.chat-users li').each(function(){
        users.push({"id": $(this).attr('user_id'), "name": $(this).text()});
    });
    /** передает список пользователей полученных из контента */
    userList.load(users);
    /** запрос на получение списка онлайн пользователей */
    chat.json.send({'type':'get_users_list'});
    /** запрос на получение кол-ва непрочитанных сообщений */
    chat.json.send({'type':'get_unread_messages'});
    /** подключение custom скролла */
    $('.chat-user-list').mCustomScrollbar({
        scrollButtons:{
            enable: true
        },
        theme: "dark-thick",
        updateOnContentResize:true,
        scrollInertia: 0
    });
    $('.chat-feed').mCustomScrollbar({
        scrollButtons:{
            enable: true
        },
        theme: "dark-thick",
        updateOnContentResize:true,
        autoHideScrollbar: true,
        scrollInertia: 0,
        callbacks:{
            /** реализация бесконечной прокрутки сообщений/истории по датам */
            onTotalScrollBack: function(){
                if($('.chat.active').length){
                    var active_user = userList.list[$('.chat.active').attr('id').split('_')[1]];
                    var offset = 0;
                    if($('ul#chat-dates').is(':visible')){
                        offset = Object.keys(active_user.messages).length;
                        active_user.first_message_id = $('.chat.active div.chat-feed blockquote:first').attr('id');
                        chat.json.send({'type':'load_previous_messages', 'data':{"uid":active_user.id, "offset": offset}});
                    }
                    if($('ul#chat-history').is(':visible')){
                        offset = Object.keys(active_user.history_dates).length;
                        chat.json.send({'type':'get_history_list', 'data':{'uid':active_user.id, 'offset': offset}})
                    }
                }
            }
        }
    });
    $("#chat-user-filter").keyup( function() {
        if($(this).val()!==''){
            $(".chat-users li").hide().filter(":containsi('"+ $(this).val() +"')").show();
        } else {
            userList.renderMenu();
        }
        $('.chat-user-list').mCustomScrollbar("update");
    });
};
/**
 * создает блок для центра уведомлений
 * @param selector - селектор к которому нужно привизать блок
 * @param content - контент блока
 */
function create_notification_center(selector, content){
    $('#_hidden_notification_center').remove();
    selector.append('<div id="_hidden_notification_center">' + content + '</div>');
}