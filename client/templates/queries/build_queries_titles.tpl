{% set status = {'open': 'Открытая', 'close': 'Закрытая', 'working': 'В работе', 'reopen': 'Открытая'} %}

{% for query in queries %}
<div class="query col-lg-9">
  <div class="row">
    <div class="alert alert-warning">
      <h4>Диспетчерская заявка</h4>
      <ul>
        <li>№{{ query.get_number() }} от {{ query.get_time_open()|date('d.m.Y') }}</li>
        <li>Описание: {{ query.get_description() }}</li>
        <li>Статус: {{ status[query.get_status()] }}</li>
      </ul>
    </div>
  </div>
</div>
{% endfor %}