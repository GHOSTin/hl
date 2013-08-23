{% extends "ajax.tpl" %}
{% set old_meter = component.old_meter %}
{% set meter = component.new_meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.number[number = {{ old_meter.get_number_id() }}] .meter[serial = {{ old_meter.get_serial() }}][meter = {{ old_meter.get_meter_id() }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ old_meter.get_number_id() }}] .meter[serial = {{ old_meter.get_serial() }}][meter = {{ old_meter.get_meter_id() }}] .get_meter_data').html('{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})');
    $('.number[number = {{ old_meter.get_number_id() }}] .meter[serial = {{ old_meter.get_serial() }}][meter = {{ old_meter.get_meter_id() }}]').attr('serial', '{{ meter.get_serial() }}');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}