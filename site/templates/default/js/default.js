function show_dialog(result){
	$('.dialog').modal('hide');
	$('.dialog').remove();
	$('body').append('<div class="dialog" style="display:none">' + result + '</div>');
	$('.dialog').modal({keyboard: false});
}
function show_content(result){
    $('._hidden_content').remove();
    $('body').append('<div class="_hidden_content" style="display:none">' + result + '</div>');
    _content();
    $('._hidden_content').remove();
}
function get_hidden_content(){
    return $('#_hidden_content').html();
}
function get_temp_html(){
    return $('#_hidden_content').html();
}
$('body').on('click', '.close_dialog', function(){
	$('.dialog').modal('hide');
});