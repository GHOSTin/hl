function show_dialog(result){
	$('.dialog').modal('hide');
	$('.dialog').remove();
	$('body').append('<div class="dialog" style="display:none">' + result + '</div>');
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
function get_hidden_content(){
    return $('._hidden_content_html').html();
}
$('body').on('click', '.close_dialog', function(){
	$('.dialog').modal('hide');
});