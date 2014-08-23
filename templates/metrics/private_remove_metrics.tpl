{% extends "ajax.tpl" %}
{% block js %}
  {% for id in component.ids %}
    $('input#{{ id }}').closest('tr').remove();
  {% endfor %}
{% endblock %}
