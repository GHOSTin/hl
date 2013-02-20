var notify = io.connect('http://192.168.2.202:3000/notify');

var tryReconnect = function(){
    if (notify.socket.connected === false &&
        notify.socket.connecting === false) {
        notify.socket.connect();
    }
}

var intervalID = setInterval(tryReconnect, 60000);

notify.on('connect', function(){
    notify.json.send({"type":"test", "data":""});
    clearInterval(intervalID);
});

notify.on('message', function(event){
    var message = event.data;
    switch (message.type) {
        case 'toggle_semaphore':
            setTimeout(function(){
                $('.notification-center-icon').removeClass('icon').addClass('icon-white');
            }, 5000);
            break;
        default:
            console.log('received unknown message:');
            console.log(message);
    }
});

notify.on('disconnect', function(){
    intervalID = setInterval(tryReconnect, 60000);
});


$('.notification-center-icon').on('click', function(){$(this).addClass('icon').removeClass('icon-white');})

