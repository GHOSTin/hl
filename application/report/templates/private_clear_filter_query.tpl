{% extends "ajax.tpl" %}
{% set filters = component.filters %}
{% block js %}
    $('.query_time_begin').val('{{ filters.time_begin|date('d.m.Y') }}');
    $('.query_time_end').val('{{ filters.time_end|date('d.m.Y') }}');
    $('.filter-status').val('all');
    $('.filter-select-street').val('all');
    $('.filter-select-house').html('<option>Ожидание...</option>').attr('disabled', true);
    $('.filter-select-department').val('all');
    $('.filter-select-worktype').val('all');
{% endblock js %}
{% block html %}
{% endblock html %}