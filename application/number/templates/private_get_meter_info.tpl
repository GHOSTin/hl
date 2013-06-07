{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% set number = component.number %}
{% block js %}
    $('.number[number = {{ number.id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.id }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul>
        <li>Название: {{ meter.name }} <a>заменить счетчик</a></li>
        <li>Серийный номер: {{ meter.serial }} <a>изменить</a></li>
        <li>Тарифность: {{ meter.rates }}</li>
        <li>Разрядность: {{ meter.capacity }}</li>
    </ul>
{% endblock html %}