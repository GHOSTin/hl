{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.meter[meter = {{ meter.get_id() }}] .get_meter_content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {{ meter.get_name() }}
{% endblock html %}