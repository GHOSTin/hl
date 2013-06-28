$(document).ready(function(){
    // возвращает содержимое буквы
    $('body').on('click', '.get_user_letter', function(){
        $.get('get_user_letter',{
            letter: $(this).text()
            },function(r){
                init_content(r);
            });
    })
});