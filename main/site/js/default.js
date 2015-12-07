function show_dialog(result){
    $('.modal-backdrop').remove();
	$('.dialog')
        .modal('hide')
        .empty()
        .append(result)
        .modal({keyboard: false})
        .modal('show');
}
function init_content(result){
    $('._hidden_content').remove();
    $('body').append('<div class="_hidden_content" style="display:none">' + result + '</div>');
    _hidden_content();
    $('._hidden_content').remove();
}
function show_content(result){
    init_content(result);
}
function get_hidden_content(tag){
    if(typeof tag !== 'undefined' && tag !== null){
        if(typeof tag === 'string')
            return $('._hidden_content_html > ' + tag).html();
        console.log('tag is not string');
    }else
        return $('._hidden_content_html').html();
}
$('body').on('click', '.close_dialog', function(){
	$('.dialog').modal('hide');
});

$(document).ready(function($){
    setTimeout(function(){window.scrollTo( 0, 1 );}, 0);
    $.valHooks.textarea = {
        get: function(elem) {
            return elem.value.replace(/\r?\n/g, "\r\n");
        }
    };
    $(window).scroll(function () {
        if ($(this).scrollTop() > 1) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });
    $('.scroll-to-top').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 400);
        return false;
    });
    $('#message-window').find('.dropdown-toggle').on('click', function(){
        $(this).parent().toggleClass('keep-open');
    }).end().on('hide.bs.dropdown', function(ev){
        var target = $(ev.target);
        if(!(target.hasClass("keep-open") || target.parents(".keep-open").length)) {
            target
                .find('.chat-user.active')
                .removeClass('active')
                .end()
                .find('.chat-discussion.active')
                .removeClass('active');
        }
        return !(target.hasClass("keep-open") || target.parents(".keep-open").length);
    });
    $('.navbar-minimalize').click(function () {
        if($("body").hasClass("mini-navbar")) {
            localStorage.setItem("collapse_menu", "on");
        } else {
            localStorage.setItem("collapse_menu", "off");
        }
    });
});

$(function() {
    if (location.pathname !== '/') {
        $('#side-menu')
            .find('li')
            .removeClass('active')
            .find('a[href^="/' + location.pathname.split("/")[1] + '"]')
            .parent()
            .addClass('active');
    }
});