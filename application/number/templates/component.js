$(document).ready(function(){
    $('body').on('click', '.get_street_content', function(){
        $.get('get_street_content',{
             id: $(this).parent().attr('street')
            },function(r){
                init_content(r);
            });
        $("#filter-numbers span.street").remove();
        $("#filter-numbers").prepend('<span class="label street" street-id="'+$(this).parent().attr('street')+'">'+$(this).text()+'<a class="close" href="#">&times;</a></span>')
        $("#filter-numbers #search-number").attr('filter', 'houses');
    });
    $('body').on('click', '.get_house_content', function(){
        $.get('get_house_content',{
             id: $(this).parent().attr('house')
            },function(r){
                init_content(r);
            });
        $("#filter-numbers span.house").remove();
        $("#filter-numbers span.street").after('<span class="label house" house-id="'+$(this).parent().attr('house')+'">'+$(this).text()+'<a class="close" href="#">&times;</a></span>');
    });
    $("#search-number").typeahead({
        source: function(query, process) {
            objects = [];
            map = {};
            var data = [];
            switch ($('#search-number').attr('filter')){
                case 'streets':
                    data = [];
                    $('.streets li').each(function(){
                        data.push({"id": $(this).attr('street'), "label": $(this).children('a').text() });
                    });
                    break;
                case 'houses':
                    data = [];
                    $('.houses li').each(function(){
                        data.push({"id": $(this).attr('house'), "label": $(this).children('a').text().replace('дом №', '') });
                    });
                    break;
            }
            $.each(data, function(i, object) {
                map[object.label] = object;
                objects.push(object.label);
            });
            process(objects);
        },
        updater: function(item) {
            switch ($('#search-number').attr('filter')){
                case 'streets':
                    $('ul.streets li[street="'+map[item].id+'"] a.get_street_content').click();
                    break;
                case 'houses':
                    $('ul.houses li[house="'+map[item].id+'"] a.get_house_content').click();
                    break;
            }
            return null;
        }
    });
});
function get_query_id(obj){
    return obj.closest('.query').attr('query_id');
}