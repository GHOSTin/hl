{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <form action="/import/streets/" method="post">
      <h2>Шаг №1: заливка улиц</h2>
      <div>
      {% if streets is empty %}
        Новых улиц не обнаружено.
      {% else %}
        Будут залиты следующие улицы
        <ul>
        {% for street in streets %}
        <li>{{ street }}</li>
        {% endfor %}
        </ul>
      {% endif %}
      </div>
      <button class="btn btn-default" type="submit">Продолжить</button>
      <a class="btn btn-default" href="/import/">Начать сначала</a>
    </form>
  </div>
</div>
{% endblock %}