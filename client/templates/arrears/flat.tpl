{% if flat %}
<h2>Задолженость по квартире №{{ flat.get_number() }} составляет
{% for number in flat.get_numbers() %}
  <u>{{ number.get_debt()|number_format(2, '.', ' ') }}</u> руб.
{% endfor %}
</h2>
{% endif %}