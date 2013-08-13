{% extends "ajax.tpl" %}
{% set number = component.numbers[0] %}
{% block js %}
    show_dialog(get_hidden_content());
    $('.update_number').click(function(){
        $.get('update_number',{
            id: {{number.id}},
            number: $('.dialog-number').val()
            },function(r){
                init_content(r);
                $('.dialog').modal('hide');
            });
    });
{% endblock js %}
{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Изменение номера лицевого счета</h3>
    </div>  
    <div class="modal-body">
        <input type="text" value="{{ number.number }}" class="dialog-number form-control">
    </div>
    <div class="modal-footer">
        <div class="btn btn-primary update_number ">Сохранить</div>
        <div class="btn btn-default close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}