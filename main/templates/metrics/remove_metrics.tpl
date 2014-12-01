{% extends "ajax.tpl" %}

{% block js %}
  {% for id in ids %}
    $('input#{{ id }}').closest('tr').remove();
  {% endfor %}
{% endblock %}
