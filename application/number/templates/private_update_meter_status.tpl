{% extends "ajax.tpl" %}
{% set meter = component.data %}
{% set enable_meters = component.enable_meters %}
{% set disable_meters = component.disable_meters %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .number-meters').html(get_hidden_content());
{% endblock js %}
{% block html %}
    {% include '@number/build_meters.tpl' %}
{% endblock html %}