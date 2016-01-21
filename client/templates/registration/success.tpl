{% extends "public.tpl" %}

{% block content %}
  <h2>Запрос на доступ успешно создан</h2>
  <p>Сотрудник управляющей компании <strong>в рабочее время</strong> обработает ваш запрос и вы получите письмо на указанный вами email.</p>
  <a class="btn btn-outline btn-primary" href="/">Вернуться на главную</a>
{% endblock content %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock css%}