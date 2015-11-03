function show_dialog(result) {
  $('.dialog').modal('hide');
  $('.dialog').remove();
  $('body').append('<div class="dialog modal fade" style="display:none">' + result + '</div>');
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

// mobile side-menu slide toggler
var $menu = $("#sidebar-nav");
$("body").click(function () {
    if ($(this).hasClass("menu")) {
        $(this).removeClass("menu");
    }
});
$menu.click(function(e) {
    e.stopPropagation();
});
$("#menu-toggler").click(function (e) {
    e.stopPropagation();
    $("body").toggleClass("menu");
});
$(window).resize(function() {
    $(this).width() > 769 && $("body.menu").removeClass("menu")
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