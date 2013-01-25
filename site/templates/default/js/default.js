function show_dialog(result){
	$('.dialog').modal('hide');
	$('.dialog').remove();
	$('body').append('<div class="dialog">'+result.html+'</div>');
	$('.dialog').modal({keyboard: false});
	helper_dialog();
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