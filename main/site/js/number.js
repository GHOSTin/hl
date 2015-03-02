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
        console.log(1);
        switch(true){
            case $(this).closest('.label').hasClass('street'):
                $('.get_street_content').siblings('.houses').remove();
                remove_badges('street');
                scrollTo($('body'));
                break;
            case $(this).closest('.label').hasClass('house'):
                $('.get_house_content').siblings('.house-content').remove();
                remove_badges('house');
                scrollTo($('ul.streets > li.street.active'));
                break;
            case $(this).closest('.label').hasClass('flat'):
                $('.get_number_content').siblings('.number-content').remove();
                remove_badges('flat');
                scrollTo($('ul.houses > li.house.active'));
                break;
        }
        $("input#search-number")
            .typeahead('setQuery', '')
            .typeahead('destroy')
            .typeahead({
                local: get_typeahead_dataset()
            })
            .focus();
    });

    $("input#search-number").typeahead({
        local: get_typeahead_dataset()
    }).on([
            'typeahead:selected',
            'typeahead:autocompleted'
        ].join(' '), function($e){
            var args = [].slice.call(arguments, 1),
                item = args[0];
            switch ($('#search-number').attr('filter')){
                case 'streets':
                    $('ul.streets li[street="'+item.id+'"] a.get_street_content').click();
                    break;
                case 'houses':
                    $('.streets li.active > ul.houses li[house="'+item.id+'"] a.get_house_content').click();
                    break;
                case 'flats':
                    $('.streets li.active ul.houses li.active > ul.numbers li[number="'+item.id+'"] a.get_number_content').click();
                    break;
            }
            return null;
    });

    $(window).scroll(function(){
        if($(window).scrollTop() > 50){
            $('.main > .navbar').addClass('navbar-fixed');
        }
        if($(window).scrollTop() < 50) {
            $('.navbar-fixed').removeClass('navbar-fixed');
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
                    $("input#search-number")
                        .typeahead('setQuery', '')
                        .typeahead('destroy')
                        .typeahead({
                            local: get_typeahead_dataset()
                        })
                        .focus();
                });
            $("#search-number")
                .prop("disabled", false)
                .attr('filter', 'houses');
            $('#filter-numbers span.street,#filter-numbers span.house,#filter-numbers span.flat').remove();
            $('#filter-numbers')
                .prepend('<span class="label label-default street" street-id="'+$(this).parent().attr('street')+'">'+$(this).text()+'<a class="close">&times;</a></span>');
            $('.street.active').removeClass('active');
            $(this).parent().addClass('active');
            scrollTo($(this).parent());
        }

    // выводит содержимое дома
    }).on('click', '.get_house_content', function(){
        if($(this).siblings().is('.house-content')) {
            $(this).siblings('.house-content').remove();
            remove_badges('house');
        } else{
            $.get('get_house_content',{
                id: $(this).parent().attr('house')
            },function(r){
                init_content(r);
                $("input#search-number")
                    .typeahead('setQuery', '')
                    .typeahead('destroy')
                    .typeahead({
                        local: get_typeahead_dataset()
                    })
                    .focus();
            });
            $("#search-number")
                .prop("disabled", false)
                .attr('filter', 'flats');
            $('#filter-numbers span.street, #filter-numbers span.house,#filter-numbers span.flat').remove();
            var street = $(this).closest('li.street');
            $('#filter-numbers')
                .prepend('<span class="label label-default street" street-id="'+street.attr('street')+'">'+street.children('.get_street_content').text()+'<a class="close">&times;</a></span>');
            $('#filter-numbers span.street')
                .after('<span class="label label-default house" house-id="'+$(this).parent().attr('house')+'">'+$(this).text()+'<a class="close">&times;</a></span>');
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
                $("input#search-number")
                    .typeahead('setQuery', '')
                    .typeahead('destroy');
            });
            $("#search-number")
                .prop("disabled", true)
                .attr('filter', 'flats');
            $('#filter-numbers span.street, #filter-numbers span.house,#filter-numbers span.flat').remove();
            var street = $(this).closest('li.street');
            $('#filter-numbers')
                .prepend('<span class="label label-default street" street-id="'+street.attr('street')+'">'+street.children('.get_street_content').text()+'<a class="close">&times;</a></span>');
            var house = $(this).closest('li.house');
            $('#filter-numbers span.street')
                .after('<span class="label label-default house" house-id="'+house.attr('house')+'">'+house.children('.get_house_content').text()+'<a class="close">&times;</a></span>');
            $("#filter-numbers span.house")
                .after('<span class="label label-default flat" flat-id="'+$(this).parent().attr('number')+'">кв. '+$(this).text().split(' ')[1]+'<a class="close">&times;</a></span>');
            $('.street.active, .house.active, .number.active').removeClass('active');
            street.addClass('active');
            house.addClass('active');
            $(this).parent().addClass('active');
            scrollTo($(this).parent());
        }

    // выводит диалог изменеия пароля в личный кабинет
    }).on('click', '.get_dialog_edit_password', function(){
        $.get('get_dialog_edit_password',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    }).on('click', '.get_dialog_edit_department', function(){
        $.get('get_dialog_edit_department',{
            house_id: get_house_id($(this))
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

    }).on('click', '.get_dialog_add_event', function(){
        $.get('get_dialog_add_event',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    }).on('click', '.get_dialog_exclude_event', function(){
        $.get('get_dialog_exclude_event',{
            id: get_number_id($(this)),
            event_id: $(this).parent().attr('event_id'),
            time: $(this).parent().attr('time')
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования ФИО владельца лицевого счета
    }).on('click', '.get_dialog_edit_number_fio', function(){
        $.get('get_dialog_edit_number_fio',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования телефона владельца лицевого счета
    }).on('click', '.get_dialog_edit_number_telephone', function(){
        $.get('get_dialog_edit_number_telephone',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });


    // выводит диалог редактирования сотового телефона владельца лицевого счета
    }).on('click', '.get_dialog_edit_number_cellphone', function(){
        $.get('get_dialog_edit_number_cellphone',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });

    // выводит диалог редактирования сотового телефона владельца лицевого счета
    }).on('click', '.get_dialog_edit_number_email', function(){
        $.get('get_dialog_edit_number_email',{
            id: get_number_id($(this))
            },function(r){
                init_content(r);
            });
    });
});

// возвращает идентификатор лицевого счетча
function get_number_id(obj){
    return obj.closest('.number').attr('number');
}

// возвращает идентификатор дома
function get_house_id(obj){
    return obj.closest('.house').attr('house');
}

function scrollTo(el){
    $('html, body').animate({
        scrollTop: $(el).offset().top-110
    }, 100);
}

function get_typeahead_dataset(){
    var data = [];
    switch ($('#search-number').attr('filter')){
        case 'streets':
            data = [];
            $('.streets > li').each(function(){
                data.push({"id": [$(this).attr('street')], "value": $(this).children('a').text(), "tokens": [$(this).children('a').text().replace(' ул', '').replace(' пр-кт', '')] });
            });
            break;
        case 'houses':
            data = [];
            $('.streets > li.active ul.houses > li').each(function(){
                data.push({"id": [$(this).attr('house')], "value": $(this).children('a').text().replace('дом №', '') });
            });
            break;
        case 'flats':
            data = [];
            $('.streets > li.active ul.houses > li.active ul.numbers > li').each(function(){
                data.push({"id": $(this).attr('number'), "value": $(this).children('a').text().replace('кв. №', '').split(' ')[0] });
            });
            break;
    }
    return data;
}