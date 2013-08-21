{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% block js %}
    $('.number[number = {{ meter.get_number_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_meter_id() }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ meter.get_number_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_meter_id() }}] .get_meter_data').html('{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y')}})');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}