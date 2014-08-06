{% extends "ajax.tpl" %}
{% set numbers = response.numbers %}
{% set house = response.house %}
{% block js %}
    $('.house[house = {{ house.get_id() }}] .house-content-content').html(get_hidden_content());
{% endblock js %}
{% block html %}
  {% include '@number/build_number_titles.tpl' %}
{% endblock html %}