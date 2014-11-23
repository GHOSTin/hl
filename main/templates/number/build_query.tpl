{% set statuses = {'open':'Открытая', 'working':'В работе', 'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
<div class="row">
  <div class="col-md-2">
  №{{ query.get_number() }} ({{ statuses[query.get_status()] }})<br>
  Дата открытия: {{ query.get_time_open()|date("H:i d.m.y") }}<br>
  </div>
  <div class="col-md-10">
   <div>Описание: {{ query.get_description() }}</div>
   <div>Причина закрытия: {{ query.get_close_reason() }}</div>
  </div>
</div>