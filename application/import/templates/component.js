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
    $('body').on('click', '.create_street', function(){
        $.get('create_street',{
            name: $('.dialog-street').text()
            },function(r){
                init_content(r);
            });
    });
    $(document).on('click', '#toggle_checkboxes', function(){
        $('tbody input[type=checkbox]').prop('checked', ($(this).is(':checked')));
    });
    $(document).on('click', '#send_import_numbers', function(){
        var data = [];
        $('table.table > tbody > tr').each(function(){
            if($(this).find('td:first-child input[type=checkbox]').is(':checked')){
                data.push({
                    'number': $(this).find('td:nth-child(3)').text().replace('л/c №', '')
                    ,'fio': ($(this).find('td:nth-child(4) select').length)?
                                $(this).find('td:nth-child(4) select').val():
                                $(this).find('td:nth-child(4)').text()
                });
            }
        });
        $.post('load_numbers', {
            'city_id': $('.dialog-city_id').attr('city_id'),
            'street_id': $('.dialog-street_id').attr('street_id'),
            'house_id': $('.dialog-house_id').attr('house_id'),
            'numbers': data
        });
    });
});