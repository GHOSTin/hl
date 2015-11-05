function show_dialog(result){
	$('.dialog').modal('hide');
	$('.dialog').remove();
    $('.modal-backdrop').remove();
	$('body').append('<div class="dialog modal fade" style="display:none"><div class="modal-dialog">' + result + '</div></div>');
	$('.dialog').modal({keyboard: false});
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