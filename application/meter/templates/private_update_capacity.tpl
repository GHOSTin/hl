{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.meter[meter = {{ meter.get_id() }}] .meter-capacity').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {{ meter.get_capacity() }}
{% endblock html %}