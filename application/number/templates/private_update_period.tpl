{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .meter-data-content').html(get_hidden_content());
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .get_meter_data').html('{{ services[meter.service] }} {{ meter.name }} №{{ meter.serial }} ({{ meter.date_next_checking|date('d.m.Y')}})');
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}