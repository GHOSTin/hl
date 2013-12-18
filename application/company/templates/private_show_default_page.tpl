{% extends "default.tpl" %}
{% set companies = component.companies %}
{% block component %}
  <ul>
  {% for company in companies %}
    <li>{{ company.get_name() }}</li>
  {% endfor %}   
  </ul>
{% endblock component %}