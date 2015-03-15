{% extends "ajax.tpl" %}

{% block js %}
$('.event_time_begin').val('{{ filters.time_begin|date('d.m.Y') }}');
$('.event_time_end').val('{{ filters.time_end|date('d.m.Y') }}');
{% endblock js %}