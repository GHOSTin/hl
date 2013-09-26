{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% set rates = ['однотарифный', 'двухтарифный', 'трехтарифный'] %}
{% block js %}
    $('.meter[meter = {{ meter.get_id() }}] .meter-rates').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {{ rates[meter.get_rates() - 1] }}
{% endblock html %}