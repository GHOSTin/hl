{% if component.numbers != false %}
<option value="0">Выберите лицевой счет</option>
	{% for number in component.numbers %}
		<option value="{{number.id}}">{{number.fio}} (№{{number.number}})</option>
	{% endfor %}
{% endif %}