{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set date = component.time %}
{% set months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август',
'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'] %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .meter-data-value').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_data.tpl' %}
{% endblock html %}