{% extends "ajax.tpl" %}
{% set number = component.number %}
{% set meter = component.meter %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ number.get_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_id() }}] .meter-data-value').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_data.tpl' %}
{% endblock html %}