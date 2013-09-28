{% extends "ajax.tpl" %}
{% set n2m = component.n2m %}
{% block js %}
    $('.number[number = {{ n2m.get_number().get_id() }}] .meter[serial = {{ n2m.get_serial() }}][meter = {{ n2m.get_meter().get_id() }}] .meter-data-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    <ul class="unstyled">
        <li><a href="/number/get_meter_cart?number_id={{ n2m.get_number().get_id() }}&meter_id={{ n2m.get_meter().get_id() }}&serial={{ n2m.get_serial() }}" target="_blank">Карточка прибора</a></li>        
    </ul>
{% endblock html %}