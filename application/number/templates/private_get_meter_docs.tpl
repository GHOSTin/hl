{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.number[number = {{ meter.number_id }}] .meter[serial = {{ meter.serial }}][meter = {{ meter.meter_id }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        <li><a href="/number/get_meter_cart?number_id={{ meter.number_id }}&meter_id={{ meter.meter_id }}&serial={{ meter.serial }}" target="_blank">Карточка прибора</a></li>        
    </ul>
{% endblock html %}