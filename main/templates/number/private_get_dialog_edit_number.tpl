{% extends "ajax.tpl" %}

{% set number = response.number %}

{% block js %}
    show_dialog(get_hidden_content());
    $('.update_number').click(function(){
        $.get('update_number',{
            id: {{ number.get_id() }},
            number: $('.dialog-number').val()
        },function(r){
            init_content(r);
            $('.dialog').modal('hide');
        });
    });
{% endblock %}

{% block html %}
<div class="modal-content">
    <div class="modal-header">
        <h3>Изменение лицевого счета</h3>
    </div>
    <div class="modal-body">
        <input type="text" value="{{ number.get_number() }}" class="dialog-number form-control">
    </div>
    <div class="modal-footer">
        <div class="btn btn-primary update_number ">Сохранить</div>
        <div class="btn btn-default close_dialog">Отмена</div>
    </div>
</div>
{% endblock %}