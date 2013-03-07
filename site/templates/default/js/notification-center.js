var log = function (param) {
    console.log(param);
};

var notify_center = io.connect('http://192.168.2.202:3000/notify');
var chat = io.connect('http://192.168.2.202:3000/chat');
 
var tryReconnect = function(){
     if (notify_center.socket.connected === false &&
         notify_center.socket.connecting === false) {
         notify_center.socket.connect();
     }
}

var intervalID = setInterval(tryReconnect, 60000);
notify_center.on('connect', function(){
    notify_center.json.send({"type":"user_ready", "data":{'uid': parseInt($('.current_user').attr('user_id'))}});
    clearInterval(intervalID);
});
notify_center.on('message', function(event){
    var message = event.data;
    switch (message.type) {
        case 'toggle_semaphore':
               $('.notification-center-icon').addClass('icon-white');
            break;
        default:
            console.log('received unknown message:');
            console.log(message);
    }
});
notify_center.on('disconnect', function(){
    intervalID = setInterval(tryReconnect, 60000);
});

chat.on('message', function (event) {
    var message = event.data;
    switch (message.type) {
//        case 'get_user_list':
//            userList.load(message.data);
//            break;
        case 'previous_messages':
            log(message.data.messages);
            userList.list[message.data.uid].loadPreviousMessages(message.data.messages, message.data.status || 'new');
            break;
        case 'updates':
            feed.process(message.data);
            break;
        default:
            log('received unknown message:');
            log(message);
    }
});

$(document).on('click', '.users li a', function () {
    $('#main').show();

    var user = userList.list[$(this).attr("user_id")];

    if (!user.previousMessagesLoaded) {
        user.loadPreviousMessages();
    }
    user.markAllAsRead();
    $(".chat h6").text(user.name);
    $("textarea").val("");
});

$(document).on('submit', 'form.message', function (e) {
    var form = $(e.target);
    var files = [];
    $('.attachments li').each(function(){
        files.push($(this).attr('id'));
    });
    chat.json.send({'type':'send_message', "data":{'to':$('.chat.active').attr('id').split('_')[1], 'message':form[0].message.value.replace(/\r\n|\r|\n/g, "<br>"), 'attachments': files.join(',')}});
    $("input[id=lefile]").attr({ value: '' });
    $(".attachments").html("");
    form[0].message.value = "";
    return false;
});


$('.light').on('click', function(){
    notify_center.json.send({"type":"test", "data":""});
});
$('#nt-center').on('click', function(){
    var self = $(this);
    $.get('/profile/get_notification_center_content',{
        },function(r){
            show_notification_center(self.parent(), r);
            baron($('.user-list'), {
                scroller: '.scroller',
                container: '.scroller_conteiner',
                bar: '.scroller__bar',
                barOnCls: 'scroller__bar_state_on'
            });
            var users = [];
            $('.users li').each(function(){
                users.push({"id": $(this).attr('user_id'), "name": $(this).text()})
            });
            userList.load(users);

            chat.json.send({"type":"user_ready", "data":{'uid': parseInt($('.current_user').attr('user_id'))}});
            $('.notification-center-icon').removeClass('icon-white');
        });
});
function show_notification_center(selector, r){
    $('#_hidden_notification_center').remove();
    selector.append('<div id="_hidden_notification_center" style="display:none">' + r + '</div>');
    $('#_hidden_notification_center').show();
}