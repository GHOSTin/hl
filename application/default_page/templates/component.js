$(document).ready(function(){
    $.get('/profile/get_notification_center_content',{
    },function(r){
        /** создать блок с полученным контентом */
        $('section.main').html('<div id="chat">'+r+'</div>');
        build_chat_window();
    });
});