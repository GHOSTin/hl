{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <form action="/import/numbers/" method="post">
      <h2>Шаг №4: импорт лицевых счетов</h2>
      <div>
      {% if numbers is empty %}
        Новых квартир не обнаружено.
      {% else %}
        Будут залиты следующие квартиры
        <ul>
        {% for number in numbers %}
        <li>{{ number }}</li>
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