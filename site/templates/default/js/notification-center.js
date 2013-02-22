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
$('#nt-center').on('click', function(){
    var self = $(this);
    $.get('index.php',{p: 'profile.get_notification_center_content'
        },function(r){
            show_notification_center(r);
            $('.notification-center-icon').removeClass('icon-white');
        });
});
function show_notification_center(r){
    $('#_hidden_notification_center').remove();
    $('#nt-center').append('<div id="_hidden_notification_center" style="display:none">' + r + '</div>');
    $('#_hidden_notification_center').show();
}