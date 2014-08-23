$(document).ready(function(){
    $('.remove_metrics').on('click', function(){
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
});