var notify = io.connect('http://192.168.2.202:3000/notify');
var tryReconnect = function(){
    if (notify.socket.connected === false &&
        notify.socket.connecting === false) {
        notify.socket.connect();
    }
}
var intervalID = setInterval(tryReconnect, 60000);
notify.on('connect', function(){
    //notify.json.send({"type":"test", "data":""});
    clearInterval(intervalID);
});

notify.on('message', function(event){
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
notify.on('disconnect', function(){
    intervalID = setInterval(tryReconnect, 60000);
});
$('.light').on('click', function(){
    notify.json.send({"type":"test", "data":""});
});

$('.notification-center-icon').on('click', function(){
    var self = $(this);
    $.get('index.php',{p: 'profile.get_notification_center_content'
            },function(r){
               self.removeClass('icon-white');
            });
});
$('.notification-center-icon').parent().popover({title: '123', placement:'bottom'});