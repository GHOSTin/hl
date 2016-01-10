$(document).ready(function(){
    $('.archive_metrics').on('click', function(){
        if($('#metrics').find('tbody input:checked').length != 0)
            $.post('remove_metrics',
                $('#metrics').serialize(),
                function(r){
                    show_content(r);
                }
            );
    });
    var checkAll = $('input#select-all');
    checkAll.on('ifChecked ifUnchecked', function(event) {
        var checkboxes = $('input.check');
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    $('.get_date_metrics').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'ru',
        ignoreReadonly: true,
        defaultDate: moment()
    }).on('dp.change', function(e){
        $.get('set_date',{
            time: e.date.format('X')
        },function(r){
            $('form#metrics').html(r);
        });
    });
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
});