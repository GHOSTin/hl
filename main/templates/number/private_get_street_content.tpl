{% extends "ajax.tpl" %}

{% set houses = response.houses %}

{% block js %}
  $('.street[street = {{ request.GET('id') }}]').append(get_hidden_content())
{% endblock %}

{% block html %}
  {% include '@number/build_houses_titles.tpl' %}
{% endblock %}