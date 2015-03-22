{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <form action="/import/houses/" method="post">
      <h2>Шаг №2: импорт домов</h2>
      <div>
      {% if houses is empty %}
        Новых домов не обнаружено.
      {% else %}
        Будут залиты следующие дома
        <ul>
        {% for house in houses %}
        <li>{{ house }}</li>
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