{% extends "default.tpl" %}

{% block component %}
	<div class="row">
		<div class="col-md-12">
      <h1>В базе нет лицевых</h1>
      <a href="/import/">Вернуться к импорту</a>
      <ul class="nav nav-pills">
      {% for number in numbers %}
        <li>{{ number }}</li>
      {% endfor %}
      </ul>
		</div>
	</div>
{% endblock component %}