<option value="all">Все дома</option>
{% for house in houses %}
	<option value="{{ house.get_id() }}">дом №{{ house.get_number() }}</option>
{% endfor %}