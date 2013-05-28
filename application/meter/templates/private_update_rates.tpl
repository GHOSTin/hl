{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.meter[meter = {{ meter.id }}] .meter-rates').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {{ rates[meter.rates - 1] }}
{% endblock html %}