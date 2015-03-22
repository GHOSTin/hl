{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <form action="/import/flats/" method="post">
      <h2>Шаг №3: импорт квартир</h2>
      <div>
      {% if flats is empty %}
        Новых квартир не обнаружено.
      {% else %}
        Будут залиты следующие квартиры
        <ul>
        {% for flat in flats %}
        <li>{{ flat }}</li>
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