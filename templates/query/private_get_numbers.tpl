<option value="0">Выберите лицевой счет</option>
{% for number in component.house.get_numbers() %}
	<option value="{{ number.get_id() }}">кв. №{{ number.get_flat().get_number() }}, {{ number.get_fio() }} (№{{ number.get_number() }})</option>
{% endfor %}