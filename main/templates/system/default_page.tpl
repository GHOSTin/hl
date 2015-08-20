{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-2">
    <ul class="nav">
      {% if user.check_access('system/api_key') %}
      <li>
        <a href="api/keys/">Ключи API</a>
      </li>
      {% endif %}
      {% if user.check_access('system/config') %}
      <li>
        <a href="config/">Конфигурация</a>
      </li>
      {% endif %}
      {% if user.check_access('system/logs') %}
      <li>
        <a href="logs/">Логи</a>
      </li>
      {% endif %}
      <li>
        <a href="query_types/">Типы заявок</a>
      </li>
      <li>
        <a href="/works/">Работы</a>
      </li>
    </ul>
  </div>
</div>
{% endblock %}