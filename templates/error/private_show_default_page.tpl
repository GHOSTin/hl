{% extends "default.tpl" %}
{% set errors = component.errors %}
{% block component %}
  <ul>
  {% for error in errors %}
    <li time="{{ error.get_time() }}" user_id="{{ error.get_user().get_id() }}">
      <h5>{{ error.get_time()|date('d.m.Y') }} {{ error.get_user().get_lastname() }} {{ error.get_user().get_firstname() }} {{ error.get_user().get_middlename() }}</h5>
      {{ error.get_text() }} <a class="delete_error">удалить</a>
    </li>
  {% endfor %}
  </ul>
{% endblock component %}
{% block javascript %}
    <script src="/js/error.js"></script>
{% endblock javascript %}