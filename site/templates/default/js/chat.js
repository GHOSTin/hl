window.userList = {
    list:{},
    load:function (list) {
        for (var id in list) {
            var user_attributes = list[id];
            this.list[user_attributes.id] = new User(user_attributes);
//            console.log(this.list[user_attributes.id]);
        }
        this.clearOnLoad();
        this.renderMenu();
        this.renderPanes();
    },
    renderMenu:function () {
        var activeTabId = $('li.active a').attr('user_id');
        $('.users li').remove();
        var online = ''; //список онлайн
        var offline = ''; //список оффлайн
        for (var id in this.list) {
            var user = this.list[id];
            var online_status = ((user.online) ? 'online' : 'offline');

            var li = '';
            li += '<li><a href="#user_' + user.id + '" class="' + online_status + '" user_id="' + user.id + '" data-toggle="tab">' + user.name;
            if (user.hasUnread())
                li += '<span class="unread_count label label-info pull-right">+' + user.unreadCount() + '</span>';
            li += '</a></li>';
            (user.online) ? online += li : offline += li;
        }
        $('.users').append(online+offline);
        $('a[user_id=' + activeTabId + "]").parent().addClass('active');
    },
    renderPanes:function () {
        var list = this.list;
        for (var i in this.list) {
            var user = this.list[i];
                var pane = '';
                pane += '<div class="chat tab-pane fade user" id="user_' + user.id + '">';
                pane += '<h6>' + user.name + '</h6>';
                pane += '<ul class="feed"></ul>';
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
        $('ul.feed').scroll(function(e){
            if($(this).scrollTop() == 0){
                var active_user = list[$('.chat.active').attr('id').split('_')[1]];
                chat.json.send({'type':'load_previous_messages', 'data':{"uid":this.id, "offset": active_user.messages.length}});
            }
        });
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
                case 3:
                    var message_id = update.shift();
                    var flags = update.shift();
                    var user_id = update.shift();

                    if (flags & 1) userList.list[user_id].messages[message_id].read();
                    break;
                case 4:
                    var message = new Message(update);
                    break;
                case 5:
                    var user_id = update[0];
                    var user = userList.list[user_id];
                    if(typeof user !== 'undefined' && user !== null) {
                        user.unread = update[1];
                        userList.renderMenu();
                    }
                    break;
                case 1:
                case 2:
                    var user_id = update[0];
                    var user = userList.list[user_id];
                    user.online = (code == 1) ? 1 : 0;
                    userList.renderMenu();

                    var date = '<span class="badge">' + this.formatDate() + '</span>';
                    var label = '';
                    if (code == 1)
                        label = '<span class="label label-info">В сети</span>';
                    else
                        label = '<span class="label label-important">Отключился</span>';
                    this.addStatus([date, label].join(' '), user);
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

//                var today = new Date();
//                if (dateParts[0] == today.getDate() && dateParts[1] == today.getMonth() + 1 && dateParts[2] == today.getFullYear())
//                    dateString = 'сегодня';
//                else {
//                    for (var part in dateParts)
//                        if (dateParts[part].toString().length == 1) dateParts[part] = "0" + dateParts[part];
//                    dateString = dateParts.join('.');
//                }
                return date_string;
            break;
            case 'time':
            default:
                var time_parts = [
                    date.getHours(),
                    date.getMinutes(),
//                    date.getSeconds(),
                ]

                for (var part in time_parts)
                    if (time_parts[part].toString().length == 1) time_parts[part] = "0" + time_parts[part];
                var time_string = time_parts.join(':');
                return time_string;
            break;
        }
    },

    addStatus:function (statusString, user) {
        $('#user_'+user.id+' ul.feed').append("<li>" + statusString + "</li>").scrollTop(9999);
    }
};

var User =
    function (data) {
        this.id = data.id;
        this.name = data.name;
        this.online = data.online;
        this.unread = data.unread;
        this.messages = {};
        this.previousMessagesLoaded = false;
    };

User.prototype.loadPreviousMessages = function (messages) {
    if (typeof messages !== "undefined" && messages !== null) {
//        this.messages = {};
//        $('#user_' + this.id + ' ul.feed').text("");
        for (var id in messages) {
            var message = messages[id];
            var unread = (!message.unread) ? 0 : 1;
            message.out = (message.uid !== parseInt($('.current_user').attr('user_id'))) ? 0 : 1;
            var flags = unread + message.out * 2;
            var params = [
                message._id,
                flags,
                this.id,
                message.date,
                null,
                message.text,
                message.attachments
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

User.prototype.markAllAsRead = function () {
    if (this.previousMessagesLoaded && this.hasUnread()) {
        chat.json.send({'type':'mark_as_read', 'data':{'mids':this.unreadMessagesIds().join(','), 'uid':this.id}});
    }
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
    if (this.outgoing) classes.push('pull-right');
    var attachments = '';

    if(typeof this.attachments !== 'undefined' && this.attachments !== '') {
        attachments += '<h6>Вложения</h6>';
        $.each(this.attachments.split(','), function(){
            attachments += '<p><button class="btn btn-link" id="' +this+ '" type="button">Скачать файл</button></p>';
        });
    }

    var messageString = '';
    var _date = new Date(this.date);
    var active_date = _date.getFullYear()+'_'+_date.getMonth()+'_'+_date.getDate();
    if(!$("#user_" + this.user.id + " ul.feed time#"+active_date).length > 0)
        messageString += '<time id="'+active_date+'">' + feed.formatDate(this.date, 'date') + '</time>';
    messageString += '<blockquote class="' + classes.join(' ') + '" id="' + this.id + '">';
    messageString += '<p>' + this.text + attachments + '</p>';
    messageString +=  feed.formatDate(this.date) + '</small>';
    messageString += '</blockquote>';
    messageString += '<div class="clearfix"></div>';

    if(this.status == 'history')
        this.prepend(messageString);
    else this.append(messageString);
};

Message.prototype.prepend = function(render) {
    $("#user_" + this.user.id + " ul.feed").prepend(render).scrollTop(20);
};

Message.prototype.append = function(render) {
    $("#user_" + this.user.id + " ul.feed").append(render).scrollTop(9999);
};