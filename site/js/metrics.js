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
    $('#select-all').change(function() {
        var checkboxes = $(this).closest('table').find('tbody :checkbox');
        if($(this).is(':checked')) {
            checkboxes.prop('checked', true);
        } else {
            checkboxes.prop('checked', false);
        }
    });
    $('.get_date_metrics').datepicker({format: 'dd.mm.yyyy', language: 'ru', todayHighlight: true}).on('changeDate', function(){
        $('.get_date_metrics').datepicker('hide');
        $.get('set_date',{
            time: $('.get_date_metrics').val()
        },function(r){
            $('form#metrics').html(r);
        });
    });
});