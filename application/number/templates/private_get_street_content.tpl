{% extends "ajax.tpl" %}
{% set houses = component.houses %}
{% block js %}
    $('.street[street = {{component.street.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@number/build_houses_titles.tpl' %}
{% endblock html %}
