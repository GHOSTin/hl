{% extends "ajax.tpl" %}
{% set house = response.house %}
{% set departments = response.departments %}
{% block js %}
    $('.house[house = {{ house.get_id() }}] .house-content-content').html(get_hidden_content())
{% endblock js %}
{% block html %}
      {% include '@number/build_house_information.tpl' %}
{% endblock html %}