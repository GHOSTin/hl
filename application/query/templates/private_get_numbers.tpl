{% extends "ajax.tpl" %}
{% block html %}
	{% if numbers != false %}
	<option value="0">Выберите лицевой счет</option>
		{% for number in numbers %}
			<option value="{{number.id}}">{{number.fio}} (№{{number.number}})</option>
		{% endfor %}
	{% endif %}
{% endblock html %}