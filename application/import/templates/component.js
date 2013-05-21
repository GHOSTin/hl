$(document).ready(function(){
    $('body').on('click', '.get_dialog_import_numbers', function(){
        $.get('get_dialog_import_numbers',{
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.get_dialog_import_meters', function(){
        $.get('get_dialog_import_meters',{
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.get_dialog_import_street', function(){
        $.get('get_dialog_import_street',{
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.get_dialog_import_house', function(){
        $.get('get_dialog_import_house',{
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.get_dialog_import_flats', function(){
        $.get('get_dialog_import_flats',{
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.create_street', function(){
        $.get('create_street',{
            name: $('.dialog-street').text(),
            city_id: $('.dialog-city_id').attr('city_id')
            },function(r){
                init_content(r);
            });
    });
    $('body').on('click', '.create_house', function(){
        $.get('create_house',{
            number: $('.dialog-house').text(),
            street_id: $('.dialog-street_id').attr('street_id')
            },function(r){
                init_content(r);
            });
    });
    $(document).on('click', '#toggle_checkboxes', function(){
        $('tbody input[type=checkbox]').prop('checked', ($(this).is(':checked')));
    }).on('click', '#send_import_numbers', function(){
        var data = [];
        $('table.table > tbody > tr').each(function(){
            if($(this).find('td:first-child input[type=checkbox]').is(':checked')){
                data.push({
                    'flat': $(this).find('td:nth-child(3)').text().replace('кв. №', ''),
                    'number': $(this).find('td:nth-child(4)').text().replace('л/c №', ''),
                    'fio': ($(this).find('td:nth-child(5) select').length)?
                                $(this).find('td:nth-child(5) select').val():
                                $(this).find('td:nth-child(5)').text()
                });
            }
        });
        $.post('load_numbers', {
            'city_id': $('.dialog-city_id').attr('city_id'),
            'street_id': $('.dialog-street_id').attr('street_id'),
            'house_id': $('.dialog-house_id').attr('house_id'),
            'numbers': data
        },function(r){
                init_content(r);
        });
    }).on('click', '#send_import_flats', function(){
        var data = [];
        $('table.table > tbody > tr').each(function(){
            if($(this).find('td:first-child input[type=checkbox]').is(':checked')){
                data.push($(this).find('td:nth-child(3)').text());
            }
        });
        $.post('load_flats', {
            'city_id': $('.dialog-city_id').attr('city_id'),
            'street_id': $('.dialog-street_id').attr('street_id'),
            'house_id': $('.dialog-house_id').attr('house_id'),
            'flats': data
        },function(r){
                init_content(r);
        });
    });
});