{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% set current = component.meter_data %}
{% set time = component.time %}
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
            id: {{ number.get_id() }},
            meter_id: {{ meter.get_id() }},
            serial: {{ meter.get_serial() }},
            number: $('.dialog-number').val(),
            time: {{ time }},
            tarif: tarifs,
            comment: $('.dialog-textarea-comment').val(),
            way: $('.dialog-select-way').val(),
            timestamp: $('.dialog-input-timestamp').val()
            },function(r){
                $('.dialog').modal('hide');
                init_content(r);
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
        <h3>Данный счетчика за {{ months[time|date("m") - 1] }} {{ time|date("Y") }}</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            {% for i in 1..meter.get_rates() %}
                <div class="span2">
                    <div>{{ i }} тариф</div>
                    <input type="text" class="dialog-tarif input-small" value="{{ current.get_values()[i - 1] }}" maxlength="{{ meter.get_capacity() }}" min="{{ data.value[i - 1] }}">
                    {{ data.value[i - 1] }}
                </div>
            {% endfor %}
        </div>
        <div>
            <label>Время передачи показания</label>
            <input type="text" class="dialog-input-timestamp" value="
            {% if current.get_timestamp() < 1 %}
                {{ "now"|date('d.m.Y') }}
            {% else %}
                {{ current.get_timestamp()|date('d.m.Y') }}
            {%endif%}
            ">
        </div>
        <div>
            <label>Способ передачи показания</label>
            <select class="dialog-select-way">
                {% for key, value in ways %}
                <option value="{{ key }}" {% if current.get_way() == key %} selected{% endif %}>{{ value }}</option>
                {% endfor %}
            </select>
        </div>
        <div>
            <label>Комментарий</label>
            <textarea style="width:90%" class="dialog-textarea-comment">{{ current.get_comment() }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <div class="btn update_meter_data">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}