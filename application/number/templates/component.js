$(document).ready(function(){
    $('body').on('click', '.get_street_content', function(){
        $.get('get_street_content',{
             id: $(this).parent().attr('street')
            },function(r){
                init_content(r);
            });
    });
});
function get_query_id(obj){
    return obj.closest('.query').attr('query_id');
}