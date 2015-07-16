{% extends "default.tpl" %}

{% block component %}
<div class="row">
  <div class="col-md-12">
    <a href="/import/">Вернуться к импорту</a>
  {% if rows is not empty %}
    <h4>Следующих лицевых нет в базе</h4>
    <ul>
      {% for row in rows %}
        <li>{{ row[0] }}</li>
      {% endfor %}
    </ul>
  {% endif %}
  </div>
</div>
{% endblock %}