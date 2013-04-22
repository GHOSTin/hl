{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ number.id }}] .meter[serial = {{ component.meter.serial }}][meter = {{ component.meter.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
<div class="meter-data">
    <h3>{{ component.time|date('Y')}}</h3>
    <table>
    {% for i in 0..11 %}
        {% set data = component.meter_data[date|date("U")] %}
            <tr class="month" time="{{ date|date("U") }}">
            <td>{{ months[i] }}</td>
            <td><input type="text" style="width:100px" value="{{ data[0] }}"></td>
            <td><input type="text" style="width:100px"  value="{{ data[1] }}"></td>
            <td><a class="get_dialog_edit_meter_data">изменить</a></td>
        </tr>
        {% set date = date|date_modify("+1 month") %}
    {% endfor %}
    </table>
</div>
{% endblock html %}