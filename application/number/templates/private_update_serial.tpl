{% extends "ajax.tpl" %}
{% set old_meter = component.old_meter %}
{% set meter = component.new_meters[0] %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.number[number = {{ old_meter.number_id }}] .meter[serial = {{ old_meter.serial }}][meter = {{ old_meter.meter_id }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ old_meter.number_id }}] .meter[serial = {{ old_meter.serial }}][meter = {{ old_meter.meter_id }}] .get_meter_data').html('{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y') }})');
    $('.number[number = {{ old_meter.number_id }}] .meter[serial = {{ old_meter.serial }}][meter = {{ old_meter.meter_id }}]').attr('serial', '{{ meter.serial }}');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}