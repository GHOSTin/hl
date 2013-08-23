{% extends "ajax.tpl" %}
{% set meter = component.meter %}
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
            id: {{ meter.get_number_id() }},
            meter_id: {{ meter.get_meter_id() }},
            serial: {{ meter.get_serial() }},
            number: $('.dialog-number').val(),
            time: {{ component.time }},
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
<div class="modal-content">
    <div class="modal-header">
        <h3>Данный счетчика за {{ months[component.time|date("m") - 1] }} {{ component.time|date("Y") }}</h3>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-xs-2">
                <div>1 тариф</div>
                <input type="text" class="dialog-tarif form-control" value="{{ current.value[0] }}" maxlength="{{ meter.get_capacity() }}" min="{{ data.value[0] }}">
                {{ data.value[0] }}
            </div>
            {% if meter.get_rates() == 2 %}
            <div class="col-xs-2">
                <div class="">2 тариф</div>
                <input type="text" class="dialog-tarif form-control" value="{{ current.value[1] }}" maxlength="{{ meter.get_capacity() }}" min="{{ data.value[1] }}">
                {{ data.value[1] }}
            </div>
            {% endif %}
            {% if meter.get_rates() == 3 %}
            <div class="col-xs-2">
                <div>2 тариф</div>
                <input type="text" class="dialog-tarif form-control" value="{{ current.value[1] }}" maxlength="{{ meter.get_capacity() }}" min="{{ data.value[1] }}">
                {{ data.value[1] }}

            </div>
            <div class="col-xs-2">
                <div class="">3 тариф</div>
                <input type="text" class="dialog-tarif form-control" value="{{ current.value[2] }}" maxlength="{{ meter.get_capacity() }}" min="{{ data.value[2] }}">
                {{ data.value[2] }}
            </div>
            {% endif %}
        </div>
        <div>
            <label>Время передачи показания</label>
            <input type="text" class="dialog-input-timestamp form-control" value="
            {% if current.timestamp < 1 %}
                {{ "now"|date('d.m.Y') }}
            {% else %}
                {{ current.timestamp|date('d.m.Y') }}
            {%endif%}
            ">
        </div>
        <div>
            <label>Способ передачи показания</label>
            <select class="dialog-select-way  form-control">
                {% for key, value in ways %}
                <option value="{{ key }}" {% if current.way == key %} selected{% endif %}>{{ value }}</option>
                {% endfor %}
            </select>
        </div>
        <div>
            <label>Комментарий</label>
            <textarea class="dialog-textarea-comment form-control" rows="5">{{ current.comment }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <div class="btn update_meter_data">Сохранить</div>
        <div class="btn close_dialog">Отмена</div>
    </div>    
</div>
{% endblock html %}