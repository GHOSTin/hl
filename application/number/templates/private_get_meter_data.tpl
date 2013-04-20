{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
    'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ number.id }}] .meter[serial = {{ component.meter.serial }}][meter = {{ component.meter.id }}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    <table class="meter-data">
    {% for i in 0..11 %}
        <tr>
            <td>{{ months[i] }}</td>
            <td><input type="text" style="width:100px"></td>
            <td><input type="text" style="width:100px"></td>
        </tr>
    {% endfor %}
    </table>
{% endblock html %}