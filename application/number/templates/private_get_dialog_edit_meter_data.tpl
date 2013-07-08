{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% set current = component.current_meter_data[component.time] %}
{% set data = component.last_data[0] %}
{% set months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август',
    'сентябрь', 'октябрь', 'ноябрь', 'декабрь'] %}
{% set ways = {'answerphone':'Автоответчик', 'telephone':'Телефон', 'fax':'Факс', 'personally':'Лично'}%}
{% block js %}
    show_dialog(get_hidden_content());
    $('.update_meter_data').click(function(){
        var tarifs = [];
         $('.dialog-tarif').each(function(){
           tarifs.push($(this).val());
        });
        $.get('update_meter_data',{
            id: {{ meter.number_id }},
            meter_id: {{ meter.meter_id }},
            serial: {{ meter.serial }},
            number: $('.dialog-number').val(),
            time: {{ component.time }},
            tarif: tarifs,
            comment: $('.dialog-textarea-comment').val(),
            way: $('.dialog-select-way').val(),
            timestamp: $('.dialog-input-timestamp').val()
            },function(r){
                init_content(r);
                $('.dialog').modal('hide');
            });
    });

    // датапикер
    $('.dialog-input-timestamp').datepicker({format: 'dd.mm.yyyy', language: 'ru'}).on('changeDate', function(){
        $('.dialog-input-timestamp').datepicker('hide');
    });
{% endblock js %}
{% block html %}
<div class="modal">
    <div class="modal-header">
        <h3>Данный счетчика за {{ months[component.time|date("m") - 1] }} {{ component.time|date("Y") }}</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="span2">
                <div>1 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[0] }}" maxlength="{{ meter.capacity }}">
                {{ data.value[0] }}
            </div>
            {% if meter.rates == 2 %}
            <div class="span2">
                <div class="">2 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[1] }}" maxlength="{{ meter.capacity }}">
                {{ data.value[1] }}
            </div>
            {% endif %}
            {% if meter.rates == 3 %}
            <div class="span2">
                <div>2 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[1] }}" maxlength="{{ meter.capacity }}">
                {{ data.value[1] }}

            </div>
            <div class="span2">
                <div class="">3 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[2] }}" maxlength="{{ meter.capacity }}">
                {{ data.value[2] }}
            </div>
            {% endif %}
        </div>
        <div>
            <label>Время передачи показания</label>
            <input type="text" class="dialog-input-timestamp" value="
            {% if current.timestamp < 1 %}
                {{ "now"|date('d.m.Y') }}
            {% else %}
                {{ current.timestamp|date('d.m.Y') }}
            {%endif%}
            ">
        </div>
        <div>
            <label>Способ передачи показания</label>
            <select class="dialog-select-way">
                {% for key, value in ways %}
                <option value="{{ key }}" {% if current.way == key %} selected{% endif %}>{{ value }}</option>
                {% endfor %}
            </select>
        </div>
        <div>
            <label>Комментарий</label>
            <textarea style="width:90%" class="dialog-textarea-comment">{{ current.comment }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <div class="btn update_meter_data">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}