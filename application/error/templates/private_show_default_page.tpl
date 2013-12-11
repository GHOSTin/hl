{% extends "default.tpl" %}
{% set errors = component.errors %}
{% block component %}
  <ul>
  {% for error in errors %}
    <li>
      <h5>{{ error.get_user().get_lastname() }} {{ error.get_user().get_firstname() }} {{ error.get_user().get_middlename() }} ({{ error.get_time()|date('d.m.Y')}})</h5>
      {{ error.get_text() }}
    </li>    
  {% endfor %}
  </ul>
{% endblock component %}