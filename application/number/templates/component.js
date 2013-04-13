$(document).ready(function(){
    $('body').on('click', '.get_street_content', function(){
        $.get('get_street_content',{
             id: $(this).parent().attr('street')
            },function(r){
                init_content(r);
            });
        $('#filter-numbers').prepend('<span class="badge badge-success">'+$(this).text()+'</span>');
    });
    $('body').on('click', '.get_house_content', function(){
        $.get('get_house_content',{
             id: $(this).parent().attr('house')
            },function(r){
                init_content(r);
            });
    });
    $("#search-number").typeahead({
        source: function(){var arr=[]; $('.streets li').each(function(){
                    arr.push($(this).children('a').text());
                });
                return arr;}()
    });
});
function get_query_id(obj){
    return obj.closest('.query').attr('query_id');
}