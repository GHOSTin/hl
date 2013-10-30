{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% block js %}
    $('.number[number = {{ request.GET('id') }}] .meter[serial = {{ request.GET('serial') }}][meter = {{ request.GET('meter_id') }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        <li><a href="/number/get_meter_cart?number_id={{ request.GET('id') }}&meter_id={{ request.GET('meter_id') }}&serial={{ request.GET('serial') }}" target="_blank">Карточка прибора</a></li>        
    </ul>
{% endblock html %}