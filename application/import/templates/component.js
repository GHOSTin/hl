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
    $(document).on('click', '#toggle_checkboxes', function(){
        $('tbody input[type=checkbox]').prop('checked', ($(this).is(':checked')));
    });
    $(document).on('click', '#send_import_numbers', function(){
        var data = [];
        $('table.table > tbody > tr').each(function(){
            if($(this).find('td:first-child input[type=checkbox]').is(':checked')){
                data.push({
                    'ls': $(this).find('td:nth-child(3)').text().replace('л/c №', '')
                    ,'fio': ($(this).find('td:nth-child(4) select').length)?
                                $(this).find('td:nth-child(4) select').val():
                                $(this).find('td:nth-child(4)').text()
                });
            }
        });
        $.post('load_numbers', {'numbers': data});
    });
});