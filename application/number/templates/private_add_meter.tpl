{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set meters = component.meters %}
{% block js %}
    $('.number[number = {{number.id}}] .number-meters').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% for meter in meters %}
        <li class="meter" meter="{{ meter.id }}" serial="{{ meter.serial }}">
            {% set period = meter.period %}
            <p class="get_meter_data">{{ services[meter.service[0]] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_checking|date('d.m.Y') }})</p>
        </li>
    {% else %}
        <li>Ни одного счетчика еще не привязано.</li>
    {% endfor %}
{% endblock html %}