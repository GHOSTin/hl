{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% set services = {'cold_water':'Холодное водоснабжение',
    'hot_water':'Горячее водоснабжение', 'electrical':'Электроэнергия'} %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.number[number = {{ request.GET('number_id') }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ request.GET('meter_id') }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ request.GET('number_id') }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ request.GET('meter_id') }}] .get_meter_data').html('{{ services[n2m.get_service()] }} {{ n2m.get_meter().get_name() }} №{{ n2m.get_serial() }} ({{ meter.get_date_next_checking()|date('d.m.Y') }})');
    $('.number[number = {{ request.GET('number_id') }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ request.GET('meter_id') }}]').attr('serial', '{{ n2m.get_serial() }}');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}