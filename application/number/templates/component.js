$(document).ready(function(){
    $('body').on('click', '.get_street_content', function(){
        $.get('get_street_content',{
             id: $(this).parent().attr('street')
            },function(r){
                init_content(r);
            });
        $("#search-number").prop("disabled", false);
        $('#filter-numbers span.street,#filter-numbers span.house,#filter-numbers span.flat').remove();
        $('#filter-numbers').prepend('<span class="label street" street-id="'+$(this).parent().attr('street')+'">'+$(this).text()+'<a class="close">&times;</a></span>');
        $('.street.active').removeClass('active');
        $(this).parent().addClass('active');
        scrollTo($(this).parent());
        $('#filter-numbers').find('#search-number').attr('filter', 'houses');
    });
    $('body').on('click', '.get_house_content', function(){
        $.get('get_house_content',{
            id: $(this).parent().attr('house')
        },function(r){
            init_content(r);
        });
        $("#search-number").prop("disabled", false);
        $('#filter-numbers span.house,#filter-numbers span.flat').remove();
        $('#filter-numbers span.street').after('<span class="label house" house-id="'+$(this).parent().attr('house')+'">'+$(this).text()+'<a class="close">&times;</a></span>');
        $('.house.active').removeClass('active');
        $(this).parent().addClass('active');
        scrollTo($(this).parent());
        $('#filter-numbers #search-number').attr('filter', 'flats');
    });
    $('body').on('click', '.get_number_content', function(){
//        $.get('get_house_content',{
//            id: $(this).parent().attr('house')
//        },function(r){
//            init_content(r);
//        });
        $("#search-number").prop("disabled", true);
        $("#filter-numbers span.flat").remove();
        $("#filter-numbers span.house").after('<span class="label flat" flat-id="'+$(this).parent().attr('number')+'">кв. '+$(this).text().split(' ')[1]+'<a class="close">&times;</a></span>');
        $('.number.active').removeClass('active');
        $(this).parent().addClass('active');
        scrollTo($(this).parent());
        $("#filter-numbers #search-number").attr('filter', 'flats');
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
                    $('.streets li.active > ul.houses li').each(function(){
                        data.push({"id": $(this).attr('house'), "label": $(this).children('a').text().replace('дом №', '') });
                    });
                    break;
                case 'flats':
                    data = [];
                    $('.streets li.active ul.houses li.active > ul.numbers li').each(function(){
                        data.push({"id": $(this).attr('number'), "label": $(this).children('a').text().replace('кв. №', '').split(' ')[0] });
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
                    $('.streets li.active > ul.houses li[house="'+map[item].id+'"] a.get_house_content').click();
                    break;
                case 'flats':
                    $('.streets li.active ul.houses li.active > ul.numbers li[number="'+map[item].id+'"] a.get_number_content').click();
                    break;
            }
            return null;
        }
    });
});
function get_query_id(obj){
    return obj.closest('.query').attr('query_id');
}

function scrollTo(el){
    $('html, body').animate({
        scrollTop: $(el).offset().top-60
    }, 100);
}