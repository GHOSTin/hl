{% extends "ajax.tpl" %}
{% set number = component.number %}
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
        <tr class="month" time="{{ i + 1}}.{{ component.time|date('Y')}}">
            <td>{{ months[i] }}</td>
            <td><input type="text" style="width:100px"></td>
            <td><input type="text" style="width:100px"></td>
            <td><a class="get_dialog_edit_meter_data">изменить</a></td>
        </tr>
    {% endfor %}
    </table>
</div>
{% endblock html %}