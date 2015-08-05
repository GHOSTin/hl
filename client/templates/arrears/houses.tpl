<option value="0">Выберите дом</option>
{% if street %}
  {% for house in street.get_houses() %}
    <option value="{{ house.get_id() }}">дом №{{ house.get_number() }}</option>
  {% endfor %}
{% endif %}