{% if numbers != false %}
	{% for number in numbers %}
		<option value="{{number.id}}">{{number.fio}} (№{{number.number}})</option>
	{% endfor %}
{% endif %}