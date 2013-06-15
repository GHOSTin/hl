{% extends "ajax.tpl" %}
{% set data = component.data %}
{% set meters = component.meters %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block js %}
    $('.number[number = {{data.number_id}}] .number-meters').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% for meter in meters %}
        <li class="meter" meter="{{ meter.meter_id }}" serial="{{ meter.serial }}">
            {% set period = meter.period %}
            <p class="get_meter_data">{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_checking|date('d.m.Y') }})</p>
        </li>
    {% else %}
        <li>Ни одного счетчика еще не привязано.</li>
    {% endfor %}
{% endblock html %}