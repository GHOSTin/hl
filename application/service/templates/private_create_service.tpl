{% extends "ajax.tpl" %}
{% set services = component.services %}
{% block js %}
    $('.services').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% for service in services %}
        {% include '@service/build_service_title.tpl' %}
    {% endfor %}
{% endblock html %}