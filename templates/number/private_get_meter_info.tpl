{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set number = component.number %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_id() }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}