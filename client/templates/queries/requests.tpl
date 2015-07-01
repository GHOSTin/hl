{% if requests %}
<p class="alert alert-warning">Ваш запрос будет обработан в кратчайшее время. Не волнуйтесь если ваш запрос долго не переходит в состояние заявки.</p>
<h4>Запросы</h4>
<ul>
{% for request in requests %}
  <li>Запрос от {{ request.get_time()|date('d.m.Y')  }}: {{ request.get_message() }}</li>
{% endfor %}
</ul>
{% endif  %}