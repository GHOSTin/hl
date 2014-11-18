$(document).ready(function(){
    $.get('/profile/get_notification_center_content',{
    },function(r){
        /** создать блок с полученным контентом */
        $('section.main').html('<div  class="col-md-6 col-sm-9 col-xs-12"><div id="chat">'+r+'</div></div>');
        build_chat_window();
    });
});