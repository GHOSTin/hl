{% extends "ajax.tpl" %}
{% set service = component.service %}
{% block js %}
    $('.service[service = {{ service.id }}] .get_service_content').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {{ service.name }}
{% endblock html %}