{% extends "ajax.tpl" %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.month[time = {{component.time}}]').html(get_hidden_content().replace(/<div>/gi, "<td>").replace(/<\/div>/gi, "</td>"));
{% endblock js %}
{% block html %}
    <div>{{months[component.time|date("m") - 1]}}</div>
    <div><input type="text" value="{{ component.meter_data[0] }}" class="tarif1 input-small"></div>
    <div><input type="text" value="{{ component.meter_data[1] }}" class="tarif2 input-small"></div>
    <div><a class="btn get_dialog_edit_meter_data">изменить</a></div>
{% endblock html %}