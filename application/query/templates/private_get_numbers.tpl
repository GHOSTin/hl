{% if component.numbers != false %}
<option value="0">Выберите лицевой счет</option>
	{% for number in component.numbers %}
		<option value="{{number.id}}">кв. №{{number.flat_number}}, {{number.fio}} (№{{number.number}})</option>
	{% endfor %}
{% endif %}