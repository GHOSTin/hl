var log = function (param) {
    console.log(param);
};

var notify_center = io.connect('http://192.168.2.202:3000/notify');
var chat = io.connect('http://192.168.2.202:3000/chat');
 
var tryReconnect = function(){
     if (notify_center.socket.connected === false &&
         notify_center.socket.connecting === false) {
         notify_center.socket.connect();
         chat.socket.connect();
     }
}

var intervalID = setInterval(tryReconnect, 60000);
notify_center.on('connect', function(){
    notify_center.json.send({"type":"user_ready", "data":{'uid': parseInt($('.current_user').attr('user_id'))}});
    clearInterval(intervalID);
});

if($('.current_user').attr('user_id').length){
    $.get('/profile/get_notification_center_content',{
    },function(r){
        create_notification_center($('#nt-center').parent(), r);
        var users = [];
        $('.users li').each(function(){
            users.push({"id": $(this).attr('user_id'), "name": $(this).text()});
        });
        userList.load(users);
        $('.user-list').mCustomScrollbar({
            scrollButtons:{
                enable: true
            },
            theme: "dark-thick",
            updateOnContentResize:true,
            autoHideScrollbar: true,
            scrollInertia: 0
        });
        $('.feed').mCustomScrollbar({
            scrollButtons:{
                enable: true
            },
            theme: "dark-thick",
            updateOnContentResize:true,
            autoHideScrollbar: true,
            scrollInertia: 0,
            callbacks:{
                onTotalScrollBack: function(){
                    var active_user = userList.list[$('.chat.active').attr('id').split('_')[1]];
                    var offset = Object.keys(active_user.messages).length;
                    active_user.first_message_id = $('.chat.active div.feed blockquote:first').attr('id');
                    chat.json.send({'type':'load_previous_messages', 'data':{"uid":active_user.id, "offset": offset}});
                }
            }
        });
        chat.json.send({"type":"user_ready", "data":{'uid': parseInt($('.current_user').attr('user_id'))}});
    });
}

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
        case 'previous_messages':
            userList.list[message.data.uid].loadPreviousMessages(message.data.messages, message.data.status || 'new');
            break;
        case 'updates':
            log(message);
            feed.process(message.data);
            break;
        default:
            log('received unknown message:');
            log(message);
    }
});

$(document).on('click', '.users li a', function () {
    var customScrollbar=$('.chat.active').find(".mCSB_scrollTools");
    customScrollbar.css({"opacity":0});
    $('.chat.active').mCustomScrollbar("update");
    customScrollbar.animate({opacity:1},"slow");
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

$(document).on('keypress', '.chat.active textarea',function (event) {
    var keyCode = (event.which ? event.which : event.keyCode);

    if (keyCode === 10 || keyCode == 13 && event.ctrlKey) {
        $(this).parent().submit();
    }
});


$('.light').on('click', function(){
    notify_center.json.send({"type":"test", "data":""});
});

$('#nt-center').on('click', function(e){
    e.preventDefault();
    $('#_hidden_notification_center').fadeToggle("fast",function(){
        var customScrollbar=$(".user-list").find(".mCSB_scrollTools");
        customScrollbar.css({"opacity":0});
        $(".user-list").mCustomScrollbar("update");
        customScrollbar.animate({opacity:1},"slow");
    });
    $('.notification-center-icon').removeClass('icon-white');
});
function create_notification_center(selector, r){
    $('#_hidden_notification_center').remove();
    selector.append('<div id="_hidden_notification_center" style="display:none">' + r + '</div>');
}