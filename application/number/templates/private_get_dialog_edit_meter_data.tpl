{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% set current = component.meter_data %}
{% set time = component.time %}
{% set last = component.last_data[0] %}
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
            id: {{ n2m.get_number().get_id() }},
            meter_id: {{ n2m.get_meter().get_id() }},
            serial: {{ n2m.get_serial() }},
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
            <div class="span2">
                <div>1 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[0] }}" maxlength="{{ n2m.get_meter().get_capacity() }}" min="{{ data.value[0] }}">
                {{ data.value[0] }}
            </div>
            {% if meter.get_rates() == 2 %}
            <div class="span2">
                <div class="">2 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[1] }}" maxlength="{{ n2m.get_meter().get_capacity() }}" min="{{ data.value[1] }}">
                {{ data.value[1] }}
            </div>
            {% endif %}
            {% if meter.get_rates() == 3 %}
            <div class="span2">
                <div>2 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[1] }}" maxlength="{{ n2m.get_meter().get_capacity() }}" min="{{ data.value[1] }}">
                {{ data.value[1] }}

            </div>
            <div class="span2">
                <div class="">3 тариф</div>
                <input type="text" class="dialog-tarif input-small" value="{{ current.value[2] }}" maxlength="{{ n2m.get_meter().get_capacity() }}" min="{{ data.value[2] }}">
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