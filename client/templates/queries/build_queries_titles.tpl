{% set status = {'open': 'Открытая', 'close': 'Закрытая', 'working': 'В работе', 'reopen': 'Открытая'} %}

<div class="queries col-md-12">
{% for query in queries %}
  <div class="well">
  {% if query.get_request() %}
    <h4>Заявка №{{ query.get_number() }} от {{ query.get_time_open()|date('d.m.Y') }} на основании запроса от {{ query.get_request().get_time()|date('d.m.Y') }}</h4>
  {% else %}
    <h4>Заявка №{{ query.get_number() }} от {{ query.get_time_open()|date('d.m.Y') }}</h4>
  {% endif %}
    <ul class="list-unstyled">
    {% if query.get_request() %}
      <li>Запрос: {{ query.get_request().get_message() }}</li>
    {% endif %}
      <li>Описание: {{ query.get_description() }}</li>
      <li>Статус: {{ status[query.get_status()] }}</li>
      <li>Диспетчер: {{ query.get_creator().get_fio() }}</li>
    {% if query.get_status() == 'close' %}
      <li>Причина закрытия: {{ query.get_close_reason() }}</li>
    {% endif %}
    </ul>
  </div>
{% endfor %}
</div>