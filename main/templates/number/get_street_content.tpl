{% extends "ajax.tpl" %}

{% block js %}
  $('.street[street = {{ street_id }}]').append(get_hidden_content())
{% endblock %}

{% block html %}
  {% include 'number/build_houses_titles.tpl' %}
{% endblock %}