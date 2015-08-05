<option value="0">Выберите квартиру</option>
{% if house %}
  {% for flat in house.get_flats().toArray()|natsort %}
    <option value="{{ flat.get_id() }}">кв. №{{ flat.get_number() }}</option>
  {% endfor %}
{% endif %}