<option>Выберите дом</option>
{% for house in street.get_houses() %}
<option value="{{ house.get_id() }}">дом №{{ house.get_number() }}</option>
{% endfor %}