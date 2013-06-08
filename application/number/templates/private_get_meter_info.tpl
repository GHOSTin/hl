{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul>
        <li>Название: {{ meter.name }} <a>заменить счетчик</a></li>
        <li>Серийный номер: {{ meter.serial }} <a>изменить</a></li>
        <li>Тарифность: {{ meter.rates }}</li>
        <li>Разрядность: {{ meter.capacity }}</li>
    </ul>
{% endblock html %}