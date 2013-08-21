{% extends "ajax.tpl" %}
{% set meter = component.meter %}
{% block js %}
    $('.number[number = {{ meter.get_number_id() }}] .meter[serial = {{ meter.get_serial() }}][meter = {{ meter.get_meter_id() }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        <li><a href="/number/get_meter_cart?number_id={{ meter.get_number_id() }}&meter_id={{ meter.get_meter_id() }}&serial={{ meter.get_serial() }}" target="_blank">Карточка прибора</a></li>        
    </ul>
{% endblock html %}