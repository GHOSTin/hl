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

$(document).ready(function(){
    setTimeout(function(){window.scrollTo( 0, 1 );}, 0);
    $(document).on('click', '.get_dialog_error_message', function(){
        $.get('/error/show_dialog',{
            },function(r){
                init_content(r);
            });
    });
});