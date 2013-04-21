{% extends "ajax.tpl" %}
{% block js %}
    show_dialog(get_hidden_content());
    $('.update_number').click(function(){
        $.get('update_number',{
            id: {{component.id}},
            number: $('.dialog-number').val()
            },function(r){
                init_content(r);
                $('.dialog').modal('hide');
            });
    });
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Изменение номера лицевого счета</h3>
    </div>  
    <div class="modal-body">
        <input type="text" value="{{ component.number }}" class="dialog-number">
    </div>
    <div class="modal-footer">
        <div class="btn update_number ">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}