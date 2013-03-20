window.userList = {
    list:{},
    load:function (list) {
        for (var id in list) {
            var user_attributes = list[id];
            this.list[user_attributes.id] = new User(user_attributes);
        }
        this.clearOnLoad();
        this.renderMenu();
        this.renderPanes();
    },
    renderMenu:function () {
        var activeTabId = $('.users li.active a').attr('user_id');
        $('.users li').remove();
        var online = '',  //список онлайн
            offline = '', //список оффлайн
            unread = '';  //список тех у кого есть непрочитанные сообщения
        for (var id in this.list) {
            var user = this.list[id];
            var online_status = ((user.online) ? 'online' : 'offline');
            var li = '';
            li += '<li><a href="#user_' + user.id + '" class="' + online_status + '" user_id="' + user.id + '" data-toggle="tab">' + user.name;
            if (user.hasUnread())
                li += '<span class="unread_count label label-info pull-right">+' + user.unreadCount() + '</span>';
            li += '</a></li>';
            if (user.hasUnread()) {
                unread += li;
            } else {
                (user.online)? online += li: offline += li;
            }
        }
        $('.users').append(unread + online + offline);
        $('a[user_id=' + activeTabId + "]").parent().addClass('active');
    },
    renderPanes:function () {
        var list = this.list;
        for (var i in this.list) {
            var user = this.list[i];
                var pane = '';
                pane += '<div class="chat tab-pane fade user" id="user_' + user.id + '">';
                pane += '<h6>' + user.name;
                pane += '<button type="button" class="history btn btn-mini pull-right" data-toggle="button">';
                pane += '<i class="icon-book"></i>';
                pane += '</button></h6>';
                pane += '<div class="feed">';
                pane += '<ul id="dates"></ul>';
                pane += '<ul id="history" style="display:none;"></ul>';
                pane += '</div>';
                pane += '<form class="well message" data-user-id="' + user.id + '">';
                pane += '<textarea name="message" placeholder="Сообщение"></textarea>';
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

window.feed = {
    process:function (updates) {
        for (var id in updates) {
            var update = updates[id];
            var code = update.shift();
            switch (code) {
                case 3: //отметить сообщение как прочитанное
                    var message_id = update.shift();
                    var flags = update.shift();
                    var user_id = update.shift();

                    if (flags & 1) userList.list[user_id].messages[message_id].read();
                    break;
                case 4: //пришло новое сообщение
                    var message = new Message(update);
                    break;
                case 5: //кол-во не прочитанных сообщений
                    var user_id = update[0];
                    var user = userList.list[user_id];
                    if(typeof user !== 'undefined' && user !== null) {
                        user.unread = update[1];
                        userList.renderMenu();
                    }
                    break;
                case 6: //подгрузка списка дат
                    new History_dates(update);
                    break;
                case 7: //загрузка истории
                    new History(update);
                    break;
                case 1: //пользователь онлайн
                case 2: //пользователь оффлайн
                    var user_id = update[0];
                    var user = userList.list[user_id];
                    if(typeof user !== 'undefined' && user !== null){
                        user.online = (code === 1) ? 1 : 0;
                        userList.renderMenu();
                    }
                    break;
                default:
                    log('received unknown update:');
                    log(update);
                    break;
            }
        }
    },

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

    addStatus:function (statusString, user) {
        $('#user_'+user.id+' div.feed ul#dates').append("<li>" + statusString + "</li>");
    },

    urlify:function(text){
        var urlRegex = /(https?:\/\/[^\s]+)/g;
        return text.replace(urlRegex, function(url) {
            return '<a href="' + url + '" target="_blank">' + url + '</a>';
        });
    }
};

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

User.prototype.loadPreviousMessages = function (messages, status) {
    if (typeof messages !== "undefined" && messages !== null) {
        for (var id in messages) {
            var message = messages[id];
            var unread = (!message.unread) ? 0 : 1;
            message.out = (message.uid !== parseInt($('.current_user').attr('user_id'))) ? 0 : 1;
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

User.prototype.unreadCount = function () {
    if (this.previousMessagesLoaded) return this.unreadMessagesIds().length;
    return this.unread;
};

User.prototype.hasUnread = function () {
    return (this.unreadCount() > 0) ? true : false;
};

User.prototype.paneActive = function () {
    return ($("a[user_id=" + this.id + "]").parent().hasClass('active')) ? true : false;
};

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

User.prototype.addMessage = function (message) {
    this.messages[message.id] = message;
    if (message.unreadAndIncoming()) {
        if (!this.previousMessagesLoaded) this.unread += 1;
        userList.renderMenu();
    }
    if (this.paneActive()) this.markAllAsRead();
};

User.prototype.addHistory_date = function (history) {
    this.history_dates[history.id] = history;
}

User.prototype.markAllAsRead = function () {
    if (this.previousMessagesLoaded && this.hasUnread()) {
        chat.json.send({'type':'mark_as_read', 'data':{'mids':this.unreadMessagesIds().join(','), 'uid':this.id}});
    }
};

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

History_dates.prototype.render = function(){
    var history_date = '';
    history_date += '<section>';
    history_date += '<header id="' + this.id + '">' + this.value + '</header>';
    history_date += '<ul></ul>';
    history_date += '</section>';

    $("#user_" + this.user.id + " div.feed ul#history").prepend(history_date);
    $("#user_" + this.user.id + " div.feed").mCustomScrollbar("update");
};

History_dates.prototype.addHistory = function(history){
    this.history[history.id] = history;
};

History_dates.prototype.loadHistory = function (messages) {
    if (typeof messages !== "undefined" && messages !== null) {
        for (var id in messages) {
            var message = messages[id];
            message.out = (message.uid !== parseInt($('.current_user').attr('user_id'))) ? 0 : 1;
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
        chat.json.send({'type':'load_previous_messages', 'data':{"uid":this.id}});
    }
};

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

History.prototype.render = function(){
    var classes = ['message'];
    (this.outgoing)? classes.push('pull-right'): classes.push('pull-left');

    var message_string = '';
    message_string += '<blockquote class="' + classes.join(' ') + '" id="' + this.id + '">';
    message_string += '<p>' + feed.urlify(this.text) + '</p>';
    message_string += '<small>' + feed.formatDate(this.time) + '</small>';
    message_string += '</blockquote>';
    message_string += '<div class="clearfix"></div>';
};

var Message =
    function (params/* id, flags, from_id, timestamp, subject, text, attachments, status*/) {
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

Message.prototype.unreadAndIncoming = function () {
    return (this.unread && !this.outgoing);
};

Message.prototype.read = function () {
    this.unread = false;

    if (!this.outgoing) {
        if (this.user.previousMessagesLoaded) this.user.unread -= 1;
        userList.renderMenu();
    }
};

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
    if(!$("#user_" + this.user.id + " div.feed section#"+active_date).length > 0) {
        if(this.status === 'history'){
            $("#user_" + this.user.id + " div.feed ul#dates").prepend('<section id="'+active_date+'"><header>' + feed.formatDate(this.date, 'date') + '</header><ul></ul></section>');
        }
        else{
            $("#user_" + this.user.id + " div.feed ul#dates").append('<section id="'+active_date+'"><header>' + feed.formatDate(this.date, 'date') + '</header><ul></ul></section>');
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

Message.prototype.prepend = function(render) {
    $("#user_" + this.user.id + " div.feed ul#dates section:first ul").prepend(render);
    $("#user_" + this.user.id + " div.feed").mCustomScrollbar("update");
    $("#user_" + this.user.id + " div.feed").mCustomScrollbar("scrollTo",'blockquote#'+this.user.first_message_id);
};

Message.prototype.append = function(render) {
    $("#user_" + this.user.id + " div.feed ul#dates section:last ul").append(render);
    $("#user_" + this.user.id + " div.feed").mCustomScrollbar("update");
    $("#user_" + this.user.id + " div.feed").mCustomScrollbar("scrollTo","bottom");
};