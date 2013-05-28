{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.meter[meter = {{ meter.id }}] .meter-capacity').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {{ meter.capacity}}
{% endblock html %}