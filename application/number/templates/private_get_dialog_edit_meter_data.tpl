{% extends "ajax.tpl" %}
{% set months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август',
    'сентябрь', 'октябрь', 'ноябрь', 'декабрь'] %}
{% block js %}
    show_dialog(get_hidden_content());
    $('.update_meter_data').click(function(){
        var tarifs = [];
         $('.dialog-tarif').each(function(){
           tarifs.push($(this).val());
        });
        $.get('update_meter_data',{
            id: {{ component.number.id }},
            meter_id: {{ component.meter.id }},
            serial: {{ component.meter.serial }},
            number: $('.dialog-number').val(),
            time: {{ component.time }},
            tarif: tarifs
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
        <strong>Предыдущий месяц</strong>
        <div class="controls controls-row previous_meter_data">
            <div class="input-prepend span2">
                <span class="add-on">1 тариф</span>
                <span class="input-small uneditable-input">{{ component.meter_data[component.time|date_modify("-1 month")|date("U")][0] }}</span>
            </div>
            <div class="input-prepend span2">
                <span class="add-on">2 тариф</span>
                <span class="input-small uneditable-input">{{ component.meter_data[component.time|date_modify("-1 month")|date("U")][1] }}</span>
            </div>
        </div>
        <strong>Текущий месяц</strong>
        <div class="controls controls-row currents_meter_data">
            <div class="input-prepend span2">
                <span class="add-on">1 тариф</span>
                <input type="text" class="dialog-tarif input-small" value="{{ component.meter_data[component.time][0] }}">
            </div>
            <div class="input-prepend span2">
                <span class="add-on">2 тариф</span>
                <input type="text" class="dialog-tarif input-small" value="{{ component.meter_data[component.time][1] }}">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="btn update_meter_data">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}