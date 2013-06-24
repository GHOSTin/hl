{% extends "ajax.tpl" %}
{% set meter = component.meters[0] %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .get_meter_data').html('{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y')}})');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}