{% if flat %}
{% set debt = 0 %}
{% for number in flat.get_numbers() %}
  {% set debt = debt + number.get_debt() %}
{% endfor %}
<h2>Задолженость по квартире №{{ flat.get_number() }} составляет <u>{{ debt|number_format(2, '.', ' ') }}</u> руб.</h2>
{% endif %}