{% extends "ajax.tpl" %}
{% set months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август',
    'сентябрь', 'октябрь', 'ноябрь', 'декабрь'] %}
{% block js %}
    show_dialog(get_hidden_content());
    $('.update_meter_data').click(function(){
        $.get('update_meter_data',{
            id: {{ component.number.id }},
            meter_id: {{ component.meter.id }},
            serial: {{ component.meter.serial }},
            number: $('.dialog-number').val(),
            time: {{ component.time }},
            tarif1: $('.dialog-tarif1').val(),
            tarif2: $('.dialog-tarif2').val()
            },function(r){
                init_content(r);
                $('.dialog').modal('hide');
            });
    });
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Данный счетчика за {{ months[component.time|date("m") - 1] }} {{ component.time|date("Y") }}</h3>
    </div>  
    <div class="modal-body">
        1 тариф <input type="text" style="width:50px" class="dialog-tarif1"> 2тариф <input type="text" style="width:50px" class="dialog-tarif2">
    </div>
    <div class="modal-footer">
        <div class="btn update_meter_data">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}