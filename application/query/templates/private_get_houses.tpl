{% extends "ajax.tpl" %}
{% block html %}
	{% if houses != false %}
		<option value="0">Выберите дом</option>
		{% for house in houses %}
			<option value="{{house.id}}">дом №{{house.number}}</option>
		{% endfor %}
	{% endif %}
{% endblock html %}