<option>Выберите лицевой счет</option>
{% for number in house.get_numbers() %}
    <option value="{{ number.get_id() }}">{{ number.get_full_number() }}</option>
{% endfor %}