<option value="all">Все дома</option>
{% for house in response.street.get_houses() %}
	<option value="{{ house.get_id() }}">дом №{{ house.get_number() }}</option>
{% endfor %}