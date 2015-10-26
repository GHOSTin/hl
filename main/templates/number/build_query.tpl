{% set statuses = {'open':'Открытая', 'working':'В работе', 'close': 'Закрытая', 'reopen':'Переоткрытая'} %}
<div class="row">
  <div class="col-md-2">
  {% if query.get_initiator() == 'number' %}
    <i class="glyphicon glyphicon-user notification-center-icon" style="font-size:12px" alt="Заявка на личевой счет"></i>
  {% else %}
    <i class="glyphicon glyphicon-home notification-center-icon" style="font-size:12px" alt="Заявка на дом"></i>
  {% endif %}
  №{{ query.get_number() }} ({{ statuses[query.get_status()] }})<br>
  Дата открытия: {{ query.get_time_open()|date("H:i d.m.y") }}<br>
  </div>
  <div class="col-md-10">
  {% if query.get_initiator() == 'number' %}
    {% for number in query.get_numbers() %}
      <div> кв.{{ number.get_flat().get_number() }} {{ number.get_number() }} ({{ number.get_fio() }})</div>
    {% endfor %}
  {% endif %}
   <div>Описание: {{ query.get_description() }}</div>
   <div>Причина закрытия: {{ query.get_close_reason() }}</div>
   <div><a href="/query/?id={{ query.get_id() }}" target="_blank">Смотреть в АДС</a></div>
  </div>
</div>