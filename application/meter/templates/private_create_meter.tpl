{% extends "ajax.tpl" %}
{% set meters = component.meters %}
{% block js %}
    $('.meters').html(get_hidden_content())
{% endblock js %}
{% block html %}
    {% for meter in meters %}
        {% include '@meter/build_meter_title.tpl' %}
    {% endfor %}
{% endblock html %}