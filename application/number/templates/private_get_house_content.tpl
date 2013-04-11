{% extends "ajax.tpl" %}
{% set numbers = component.numbers %}
{% block js %}
    $('.house[house = {{component.house.id}}]').append(get_hidden_content())
{% endblock js %}
{% block html %}
    {% include '@number/build_number_titles.tpl' %}
{% endblock html %}