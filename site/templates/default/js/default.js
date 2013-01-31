function show_dialog(result){
	$('.dialog').modal('hide');
	$('.dialog').remove();
	$('body').append('<div class="dialog">'+result+'</div>');
	$('.dialog').modal({keyboard: false});
	helper_dialog();
}

function show_content(r){
    $('#_hidden_content').remove();
    $('body').append('<div id="_hidden_content" style="display:none">' + r + '</div>');
    _content();
}

function get_temp_html(){
    return $('#_hidden_content').html();
}

$('body').on('click', '.close_dialog', function(){
	$('.dialog').modal('hide');
});

!function ($) {

    $(function(){

        var $window = $(window);

        $("a[rel=popover_notifications]")
            .popover({
                title: "<a href='#'>Посмотреть все оповещения</a>",
                placement: "bottom",
                content: ''
            })
            .click(function(e) {
                e.preventDefault()
            });
        })

}(window.jQuery);