{% extends "private.tpl" %}

{% block content %}
<div class="content row">
  <div class="col-md-6">
    <h2>Настройки</h2>
    <ul class="nav">
      <li>
        <a href="/settings/password/">Изменение пароля</a>
      </li>
      <li>
        <a href="/settings/email/">Изменение email</a>
      </li>
    </ul>
  </div>
</div>
{% endblock %}