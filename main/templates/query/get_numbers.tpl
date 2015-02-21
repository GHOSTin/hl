<option value="0">Выберите лицевой счет</option>
{% for flat in house.get_flats().getValues()|natsort %}
  {% for number in flat.get_numbers() %}
  <option value="{{ number.get_id() }}">кв. №{{ number.get_flat().get_number() }} {{ number.get_fio() }} (№{{ number.get_number() }})</option>
  {% endfor %}
{% endfor %}