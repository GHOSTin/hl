{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.number[number = {{ meter.get_number_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_meter_id() }}] .meter-data-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@number/build_meter_info.tpl' %}
{% endblock html %}