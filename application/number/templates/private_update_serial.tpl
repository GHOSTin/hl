{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ meter.get_id() }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ number.get_id() }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ meter.get_id() }}] .get_meter_data').html('{{ services[meter.get_service()] }} {{ meter.get_name() }} №{{ meter.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})');
    $('.number[number = {{ number.get_id() }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ meter.get_id() }}]').attr('serial', '{{ meter.get_serial() }}');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}