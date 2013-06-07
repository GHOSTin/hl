$(document).ready(function(){
    var remove_badges = function(level){
        switch(level){
            case 'street':
                $("#search-number")
                    .prop("disabled", false)
                    .attr('filter', 'streets');
                $('.street.active, .house.active, .number.active').removeClass('active');
                $('#filter-numbers span.street,#filter-numbers span.house,#filter-numbers span.flat').remove();
                break;
            case 'house':
                $("#search-number")
                    .prop("disabled", false)
                    .attr('filter', 'houses');
                $('.house.active, .number.active').removeClass('active');
                $('#filter-numbers span.house,#filter-numbers span.flat').remove();
                break;
            case 'flat':
                $("#search-number")
                    .prop("disabled", false)
                    .attr('filter', 'flats');
                $('.number.active').removeClass('active');
                $('#filter-numbers span.flat').remove();
                break;
        }
    };

    $(document).on('click', '#filter-numbers span a.close', function(){
        switch(true){
            case $(this).closest('.label').hasClass('street'):
                remove_badges('street');
                scrollTo($('body'));
                break;
            case $(this).closest('.label').hasClass('house'):
                remove_badges('house');
                scrollTo($('ul.streets > li.street.active'));
                break;
            case $(this).closest('.label').hasClass('flat'):
                remove_badges('flat');
                scrollTo($('ul.houses > li.house.active'));
                break;
        }
    });

    $("#search-number").typeahead({
        source: function(query, process) {
            objects = [];
            map = {};
            var data = [];
            switch ($('#search-number').attr('filter')){
                case 'streets':
                    data = [];
                    $('.streets > li').each(function(){
                        data.push({"id": $(this).attr('street'), "label": $(this).children('a').text() });
                    });
                    break;
                case 'houses':
                    data = [];
                    $('.streets > li.active ul.houses > li').each(function(){
                        data.push({"id": $(this).attr('house'), "label": $(this).children('a').text().replace('дом №', '') });
                    });
                    break;
                case 'flats':
                    data = [];
                    $('.streets > li.active ul.houses > li.active ul.numbers > li').each(function(){
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

    $(window).scroll(function(){
        if($(window).scrollTop() > 20){
            $('.main > .navbar').addClass('nav-fixed');
        }
        if($(window).scrollTop() < 20) {
            $('.nav-fixed').removeClass('nav-fixed');
        }
    });
    
    // выводит содержимое улицы
    $('body').on('click', '.get_street_content', function(){
        if($(this).siblings().is('.houses')){
            $(this).siblings('.houses').remove();
            remove_badges('street');
        } else{
            $.get('get_street_content',{
                 id: $(this).parent().attr('street')
                },function(r){
                    init_content(r);
                });
            $("#search-number")
                .prop("disabled", false)
                .attr('filter', 'houses');
            $('#filter-numbers span.street,#filter-numbers span.house,#filter-numbers span.flat').remove();
            $('#filter-numbers')
                .prepend('<span class="label street" street-id="'+$(this).parent().attr('street')+'">'+$(this).text()+'<a class="close">&times;</a></span>');
            $('.street.active').removeClass('active');
            $(this).parent().addClass('active');
            scrollTo($(this).parent());
        }

    // выводит диалог для добавления счетчика
    }).on('click', '.get_dialog_add_meter', function(){
        $.get('get_dialog_add_meter',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит содержимое дома
    }).on('click', '.get_house_content', function(){
        if($(this).siblings().is('.numbers')) {
            $(this).siblings('.numbers').remove();
            remove_badges('house');
        } else{
            $.get('get_house_content',{
                id: $(this).parent().attr('house')
            },function(r){
                init_content(r);
            });
            $("#search-number")
                .prop("disabled", false)
                .attr('filter', 'flats');
            $('#filter-numbers span.street, #filter-numbers span.house,#filter-numbers span.flat').remove();
            var street = $(this).closest('li.street');
            $('#filter-numbers')
                .prepend('<span class="label street" street-id="'+street.attr('street')+'">'+street.children('.get_street_content').text()+'<a class="close">&times;</a></span>');
            $('#filter-numbers span.street')
                .after('<span class="label house" house-id="'+$(this).parent().attr('house')+'">'+$(this).text()+'<a class="close">&times;</a></span>');
            $('.street.active, .house.active, .number.active').removeClass('active');
            street.addClass('active');
            $(this).parent().addClass('active');
            scrollTo($(this).parent());
        }

    // вывод содержимое лицевого счета
    }).on('click', '.get_number_content', function(){
        if($(this).siblings().is('.number-content')){
            $(this).siblings('.number-content').remove();
            remove_badges('flat');
        } else{
            $.get('get_number_content',{
                id: $(this).parent().attr('number')
            },function(r){
                init_content(r);
            });
            $("#search-number")
                .prop("disabled", true)
                .attr('filter', 'flats');
            $('#filter-numbers span.street, #filter-numbers span.house,#filter-numbers span.flat').remove();
            var street = $(this).closest('li.street');
            $('#filter-numbers')
                .prepend('<span class="label street" street-id="'+street.attr('street')+'">'+street.children('.get_street_content').text()+'<a class="close">&times;</a></span>');
            var house = $(this).closest('li.house');
            $('#filter-numbers span.street')
                .after('<span class="label house" house-id="'+house.attr('house')+'">'+house.children('.get_house_content').text()+'<a class="close">&times;</a></span>');
            $("#filter-numbers span.house")
                .after('<span class="label flat" flat-id="'+$(this).parent().attr('number')+'">кв. '+$(this).text().split(' ')[1]+'<a class="close">&times;</a></span>');
            $('.street.active, .house.active, .number.active').removeClass('active');
            street.addClass('active');
            house.addClass('active');
            $(this).parent().addClass('active');
            scrollTo($(this).parent());
        }

    // выводит счетчики привязанные к лицевому счету
    }).on('click', '.get_meters', function(){
        $.get('get_meters',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит информацию лицеого счета
    }).on('click', '.get_number_information', function(){
        $.get('get_number_information',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит содержимое счетчика
    }).on('click', '.get_meter_data', function(){
        if($(this).siblings().is('.meter-data'))
            $(this).siblings('.meter-data').remove();
        else
            $.get('get_meter_data',{
                id: get_number_id($(this)),
                meter_id: $(this).parent().attr('meter'),
                serial: $(this).parent().attr('serial')
                },function(r){
                    init_content(r);
                });
    
    // возвращает показания счетчика привязаного к лицевому счету
    }).on('click', '.get_meter_value', function(){
        $.get('get_meter_value',{
            id: get_number_id($(this)),
            meter_id: get_meter_id($(this)),
            serial: get_meter_serial($(this))
            },function(r){
                init_content(r);
            });

    // возвращает информацию о счетчике привязаном к лицевому счету
    }).on('click', '.get_meter_info', function(){
        $.get('get_meter_info',{
            id: get_number_id($(this)),
            meter_id: get_meter_id($(this)),
            serial: get_meter_serial($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования показания счетчика
    }).on('click', '.get_dialog_edit_meter_data', function(){
        $.get('get_dialog_edit_meter_data',{
            id: get_number_id($(this)),
            meter_id: $(this).closest('.meter').attr('meter'),
            serial: $(this).closest('.meter').attr('serial'),
            time: $(this).parent().parent().attr('time')
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования счетчика
    }).on('click', '.get_dialog_edit_number', function(){
        $.get('get_dialog_edit_number',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит данные конкретного года
    }).on('click', '.get_meter_data_year', function(){
        var self = $(this);
        $.get('get_meter_data', {
            id: get_number_id($(this)),
            meter_id: $(this).closest('.meter').attr('meter'),
            serial: $(this).closest('.meter').attr('serial'),
            time: $(this).attr('act')
            },function(r){
                self.closest('.meter-data').remove();
                init_content(r);
            });
    });
});

// возвращает идентификатор лицевого счетча
function get_number_id(obj){
    return obj.closest('.number').attr('number');
}

// возвращает идентификатор счетчика
function get_meter_id(obj){
    return obj.closest('.meter').attr('meter');
}

// возвращает серийный номер счетчика
function get_meter_serial(obj){
    return obj.closest('.meter').attr('serial');
}

function scrollTo(el){
    $('html, body').animate({
        scrollTop: $(el).offset().top-110
    }, 100);
}