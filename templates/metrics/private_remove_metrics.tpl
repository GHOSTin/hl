{% extends "ajax.tpl" %}
{% block js %}
  {% for id in response.ids %}
    $('input#{{ id }}').closest('tr').remove();
  {% endfor %}
{% endblock %}
