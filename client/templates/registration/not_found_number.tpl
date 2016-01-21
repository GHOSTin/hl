{% extends "public.tpl" %}

{% block content %}
  <h2>Лицевого счета №{{ number }} не найдено</h2>
  <p>Возможно вы ошиблись или управляющая компания не завела ваш счет в систенму.</p>
  <a class="btn btn-outline btn-primary" href="/registration/">Начать сначала</a>
{% endblock content %}

{% block css %}
  <link rel="stylesheet" href="/css/default.css">
{% endblock css%}