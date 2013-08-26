{% extends "ajax.tpl" %}
{% set numbers = component.numbers %}
{% set house = component.house %}
{% block js %}
    $('.house[house = {{ house.id }}] .house-content-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
  {% include '@number/build_number_titles.tpl' %}
{% endblock html %}