/**
 * @namespace экземпляр userList
 * @type {{list: {}, load: Function, renderMenu: Function, renderPanes: Function, clearOnLoad: Function}}
 */
window.userList = {
    list:{},
    /**
     * Создает массив пользователей
     * @this {userList}
     * @param {Array} list Список атрибутов пользователей
     */
    load:function (list) {
        for (var id in list) {
            var user_attributes = list[id];
            /** @private */ this.list[user_attributes.id] = new User(user_attributes);
        }
        this.clearOnLoad();
        this.renderMenu();
        this.renderPanes();
    },
    /**
     * Создает рендер списка пользователей
     * @this {userList}
     */
    renderMenu:function () {
        var activeTabId = $('.chat-users li.active a').attr('user_id');
        $('.chat-users li').remove();
        var online = '',  /** {String} Список онлайн*/
            offline = '', /** {String} список оффлайн */
            unread = '';  /** {String} список тех у кого есть непрочитанные сообщения*/
        for (var id in this.list) {
            var user = this.list[id];
            var online_status = ((user.online) ? 'online' : 'offline');
            var li = '';
            li += '<li class="' + online_status + '"><a href="#chat-user_' + user.id + '" user_id="' + user.id + '" data-toggle="tab">' + user.name;
            if (user.hasUnread())
                li += '<span class="unread_count label label-info pull-right">+' + user.unreadCount() + '</span>';
            li += '</a></li>';
            if (user.hasUnread()) {
                unread += li;
            } else {
                (user.online)? online += li: offline += li;
            }
        }
        $('.chat-users').append(unread + online + offline);
        $('a[user_id=' + activeTabId + "]").parent().addClass('active');
    },
    /**
     * Создание рендера блоков переписки для каждого пользователя
     * @this {userList}
     */
    renderPanes:function () {
        var list = this.list;
        for (var i in this.list) {
            var user = this.list[i];
                var pane = '';
                pane += '<div class="chat tab-pane fade user" id="chat-user_' + user.id + '">';
                pane += '<h4>' + user.name;
                pane += '<button type="button" class="chat-history btn btn-mini" data-toggle="button">';
                pane += '<i class="glyphicon glyphicon-book"></i>';
                pane += '</button></h4>';
                pane += '<div class="chat-feed">';
                pane += '<ul id="chat-dates"></ul>';
                pane += '<ul id="chat-history" style="display:none;"></ul>';
                pane += '</div>';
                pane += '<form class="well message" data-user-id="' + user.id + '"><div class="form-group">';
                pane += '<textarea name="message" placeholder="Сообщение" class="form-control"></textarea></div>';
//                pane += '<a class="attach pull-right" href="#">Прикрепить</a>';
                pane += '<button class="btn btn-primary" type="submit">Отправить</button>';
                pane += '<ul class="attachments nav nav-tabs nav-stacked"></ul>';
                pane += '<input type="file" id="lefile" style="display:none;">';
                pane += '</form>';
                pane += '</div>';

                $('#main').append(pane);
        }
    },
    clearOnLoad:function () {
    }
};
/**
 * @namespace экземпляр Feed
 * @type {{process: Function, formatDate: Function, addStatus: Function, urlify: Function}}
 */
window.feed = {
    /**
     * Функция обработки обновлений контента
     * @function
     * @param {Array} updates - массив обновлений формата [code,...,params]
     */
    process:function (updates) {
        for (var id in updates) {
            var update = updates[id];
            var code = update.shift();
            switch (code) {
                case 3: /** @lends отметить сообщение как прочитанное */
                    var message_id = update.shift();
                    var flags = update.shift();
                    var user_id = update.shift();

                    if (flags & 1) userList.list[user_id].messages[message_id].read();
                    break;
                case 4: /** @lends пришло новое сообщение */
                    chat.json.send({'type':'get_unread_count'});
                    var message = new Message(update);
                    break;
                case 5: /** @lends кол-во не прочитанных сообщений */
                    var user_id = update[0];
                    var user = userList.list[user_id];
                    if(typeof user !== 'undefined' && user !== null) {
                        user.unread = update[1];
                        userList.renderMenu();
                    }
                    break;
                case 6: /** @lends подгрузка списка дат */
                    new History_dates(update);
                    break;
                case 7: /** @lends загрузка истории */
                    new History(update);
                    break;
                case 1: /** @lends пользователь онлайн */
                case 2: /** @lends пользователь оффлайн */
                    var user_id = update[0];
                    var user = userList.list[user_id];
                    if(typeof user !== 'undefined' && user !== null){
                        user.online = (code === 1) ? 1 : 0;
                        userList.renderMenu();
                    }
                    break;
                default: /** @lends если код не был обнаружен */
                    log('received unknown update:');
                    log(update);
                    break;
            }
        }
    },
    /**
     * Функция преобразования даты/время
     * @function
     * @param {Number} _date Значение timestamp
     * @param {String} _status "date" - если нужно вернуть дату, 'time' - если время
     * @returns {string} возвращает преобразованную строку с датой/временем
     */
    formatDate:function (_date, _status) {
        var status = _status || 'time';
        var date = (typeof _date!=='undefined' && _date !==null)?new Date(_date):new Date();
        switch (status) {
            case 'date':
                var date_parts = [
                    date.getDate(),
                    date.getMonth(),
                    date.getFullYear(),
                ];
                var months = [
                    'января',
                    'февраля',
                    'марта',
                    'апреля',
                    'мая',
                    'июня',
                    'июля',
                    'августа',
                    'сентября',
                    'октября',
                    'ноября',
                    'декабря',
                ];
                var date_string = date_parts[0] + " " + months[date_parts[1]];
                return date_string;
            break;
            case 'time':
            default:
                var time_parts = [
                    date.getHours(),
                    date.getMinutes()
                ]

                for (var part in time_parts)
                    if (time_parts[part].toString().length == 1) time_parts[part] = "0" + time_parts[part];
                var time_string = time_parts.join(':');
                return time_string;
            break;
        }
    },
    /**
     * Функция прикрепляет уведомление в панель чата о состоянии пользователя
     * @deprecated Убрана в версии mshc 2.0, т.к. занимала много места
     * @param {string} statusString "В сети"/"Вышел из сети"
     * @param {User} user Пользователь статус которого изменился
     */
    addStatus:function (statusString, user) {
        $('#chat-user_'+user.id+' div.feed ul#dates').append("<li>" + statusString + "</li>");
    },
    /**
     * @function
     * Ищет есть ли в тексте url и заменяет их на ссылки
     * @param {string} text Текст который нужно преобразовать
     * @returns {XML|string|void} Преобразованные url
     */
    urlify:function(text){
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank">' + url + '</a>';
        });
    }
};
/**
 * Создает экземпляр User
 * @param {Array} data JSON-массив входящих параметров пользователя
 * @constructor
 */
var User =
    function (data) {
        this.id = data.id;
        this.name = data.name;
        this.online = data.online;
        this.unread = data.unread || 0;
        this.messages = {};
        this.history_dates = {};
        this.previousMessagesLoaded = false;
    };
/**
 * Загружает предыдущие сообщения
 * @param {Array} messages массив сообщений
 * @param {string} status по умолчанию "history"
 */
User.prototype.loadPreviousMessages = function (messages, status) {
    if (typeof messages !== "undefined" && messages !== null) {
        for (var id in messages) {
            var message = messages[id];
            var unread = (!message.unread) ? 0 : 1;
            message.out = (message.uid !== parseInt(get_cookie('uid'))) ? 0 : 1;
            var flags = unread + message.out * 2;
            var params = [
                message._id,
                flags,
                this.id,
                message.date+message.time,
                null,
                message.text,
                message.attachments,
                status
            ];
            new Message(params);
        }
        this.previousMessagesLoaded = true;
        if (this.paneActive()) {
            this.markAllAsRead();
        }
    }
    else {
        chat.json.send({'type':'load_previous_messages', 'data':{"uid":this.id}});
    }
};
/**
 * запрашивает кол-во непрочитанных сообщений
 * @returns {Number} кол-во непрочитанных сообщений
 */
User.prototype.unreadCount = function () {
    if (this.previousMessagesLoaded) return this.unreadMessagesIds().length;
    return this.unread;
};
/**
 * проверяет есть ли непрочитанные сообщения
 * @returns {boolean}
 */
User.prototype.hasUnread = function () {
    return (this.unreadCount() > 0) ? true : false;
};
/**
 * проверка на активность текущей панели пользователя
 * @returns {boolean}
 */
User.prototype.paneActive = function () {
    return ($("a[user_id=" + this.id + "]").parent().hasClass('active')) ? true : false;
};
/**
 * получает массив id непрочитанных сообщений
 * @returns {Array}
 */
User.prototype.unreadMessagesIds = function () {
    var _results;
    _results = [];
    for (var id in this.messages) {
        var message = this.messages[id];
        if (message.unreadAndIncoming()) {
            _results.push(id);
        }
    }
    return _results;
};
/**
 * добавляет новое сообщение
 * @param {Message} message - объект класса Message
 */
User.prototype.addMessage = function (message) {
    this.messages[message.id] = message;
    if (message.unreadAndIncoming()) {
        if (!this.previousMessagesLoaded) this.unread += 1;
        userList.renderMenu();
    }
    if (this.paneActive()) this.markAllAsRead();
};
/**
 * добавляет в список истории дат новую дату
 * @param {History_dates} history - объект класса History_date
 */
User.prototype.addHistory_date = function (history) {
    this.history_dates[history.id] = history;
}
/**
 * отмечает все непрочитанные сообщения, как прочитанные
 */
User.prototype.markAllAsRead = function () {
    if (this.previousMessagesLoaded && this.hasUnread()) {
        chat.json.send({'type':'get_unread_count'});
        chat.json.send({'type':'mark_as_read', 'data':{'mids':this.unreadMessagesIds().join(','), 'uid':this.id}});
    }
};
/**
 * создает экземпляр History_dates
 * @param {Array} params - массив параметров объекта [id, user_id]
 * @constructor
 */
var History_dates =
    function(params){
        this.id = params[0];
        this.value = feed.formatDate(params[0], 'date');
        this.user = userList.list[params[1]];
        this.history = {};
        this.history_loaded = false;

        this.user.addHistory_date(this);
        this.render();
    };
/**
 * создает рендер блока даты для истории сообщений
 */
History_dates.prototype.render = function(){
    var history_date = '';
    history_date += '<section>';
    history_date += '<header id="' + this.id + '">' + this.value + '<span class="caret pull-right"></span></header>';
    history_date += '<ul style="display:none;"></ul>';
    history_date += '</section>';

    $("#chat-user_" + this.user.id + " div.chat-feed ul#chat-history").prepend(history_date);
    $("#chat-user_" + this.user.id + " div.chat-feed").mCustomScrollbar("update");
};
/**
 * добавляет сообщение из истории в конкретную дату
 * @param {History} history - объект класса history
 */
History_dates.prototype.addHistory = function(history){
    this.history[history.id] = history;
};
/**
 * очищает конкретную дату сообщений
 */
History_dates.prototype.clear = function(){
    this.history = {};
    $('header#'+this.id).siblings('ul').empty();
};
/**
 * загружает сообщения за конкретную дату
 * @param {Array} messages - массив сообщений
 */
History_dates.prototype.loadHistory = function (messages) {
    if (typeof messages !== "undefined" && messages !== null) {
        this.clear();
        for (var id in messages) {
            var message = messages[id];
            message.out = (message.uid !== parseInt(get_cookie('uid'))) ? 0 : 1;
            var flags = message.out;
            var params = [
                message._id,
                flags,
                this.user.id,
                this.id,
                message.date+message.time,
                message.text
            ];
            new History(params);
        }
        this.history_loaded = true;
    }
    else {
        chat.json.send({'type':'get_history_day', 'data':{"uid":this.user.id, "date": this.id}});
    }
};
/**
 * создает экземпляр History
 * @param {Array} params - массив параметров объекта [id, flags, user_id, date, time, text]
 * @constructor
 */
var History =
    function(params){
        this.id = params[0];
        this.user = userList.list[params[2]];
        this.date = this.user.history_dates[params[3]];
        this.outgoing = !!(params[1] & 1);
        this.text = params[5];
        this.time = new Date(params[4]);

        this.date.addHistory(this);
        this.render();
    };
/**
 * создает рендер блока даты для истории сообщений
 */
History.prototype.render = function(){
    var classes = ['message'];
    (this.outgoing)? classes.push('pull-right'): classes.push('pull-left');

    var message_string = '';
    message_string += '<blockquote class="' + classes.join(' ') + '" id="' + this.id + '">';
    message_string += '<p>' + feed.urlify(this.text) + '</p>';
    message_string += '<small>' + feed.formatDate(this.time) + '</small>';
    message_string += '</blockquote>';
    message_string += '<div class="clearfix"></div>';

    $('header#'+this.date.id).siblings('ul').append(message_string);
    $(".chat.active .chat-feed").mCustomScrollbar("update");
};
/**
 * создает экземпляр Message
 * @param {Array} params массив параметров объекта [id, flags, from_id, timestamp, subject, text, attachments, status]
 * @constructor
 */
var Message =
    function (params) {
        this.id = params[0];
        this.status = params[7] || 'new';
        this.subject = params[4];
        this.text = params[5];
        this.attachments = params[6];
        this.unread = !!(params[1] & 1);
        this.outgoing = !!(params[1] & 2);
        this.user = userList.list[params[2]];
        this.date = new Date(params[3]);

        this.user.addMessage(this);
        this.render();
    };
/**
 * проверяет на соответствие условиям, что сообщение непрочитано и входящее
 * @returns {boolean}
 */
Message.prototype.unreadAndIncoming = function () {
    return (this.unread && !this.outgoing);
};
/**
 * отмечает сообщение как прочитанное
 */
Message.prototype.read = function () {
    this.unread = false;

    if (!this.outgoing) {
        if (this.user.previousMessagesLoaded) this.user.unread -= 1;
        userList.renderMenu();
    }
};
/**
 * создает рендер сообщения
 */
Message.prototype.render = function () {
    var classes = ['message'];
    (this.outgoing)? classes.push('pull-right'): classes.push('pull-left');
    var attachments = '';

    if(typeof this.attachments !== 'undefined' && this.attachments !== '') {
        attachments += '<h6>Вложения</h6>';
        $.each(this.attachments.split(','), function(){
            attachments += '<p><button class="btn btn-link" id="' +this+ '" type="button">Скачать файл</button></p>';
        });
    }

    var message_string = '';
    var _date = new Date(this.date);
    var active_date = _date.getFullYear()+'_'+_date.getMonth()+'_'+_date.getDate();
    if(!$("#chat-user_" + this.user.id + " div.chat-feed section#"+active_date).length > 0) {
        if(this.status === 'history'){
            $("#chat-user_" + this.user.id + " div.chat-feed ul#chat-dates").prepend('<section id="'+active_date+'"><header>' + feed.formatDate(this.date, 'date') + '</header><ul></ul></section>');
        }
        else{
            $("#chat-user_" + this.user.id + " div.chat-feed ul#chat-dates").append('<section id="'+active_date+'"><header>' + feed.formatDate(this.date, 'date') + '</header><ul></ul></section>');
        }
    }
    message_string += '<blockquote class="' + classes.join(' ') + '" id="' + this.id + '">';
    message_string += '<p>' + feed.urlify(this.text) + attachments + '</p>';
    message_string += '<small>' + feed.formatDate(this.date) + '</small>';
    message_string += '</blockquote>';
    message_string += '<div class="clearfix"></div>';

    if(this.status === 'history')
        this.prepend(message_string);
    else this.append(message_string);
};
/**
 * вызывается если сообщение является историей
 * @param {string} render - рендер сообщения
 */
Message.prototype.prepend = function(render) {
    $("#chat-user_" + this.user.id + " div.chat-feed ul#chat-dates section:first ul").prepend(render);
    $("#chat-user_" + this.user.id + " div.chat-feed").mCustomScrollbar("update");
    $("#chat-user_" + this.user.id + " div.chat-feed").mCustomScrollbar("scrollTo",'blockquote#'+this.user.first_message_id);
};
/**
 * вызывается если сообщение новое
 * @param {string} render - рендер сообщения
 */
Message.prototype.append = function(render) {
    $("#chat-user_" + this.user.id + " div.chat-feed ul#chat-dates section:last ul").append(render);
    $("#chat-user_" + this.user.id + " div.chat-feed").mCustomScrollbar("update");
    $("#chat-user_" + this.user.id + " div.chat-feed").mCustomScrollbar("scrollTo","bottom");
};