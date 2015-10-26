<div class="row">
  <div class="col-md-2">
    <ul>
      <li>
        <a class="get_active_outages">Активные</a>
      </li>
      <li>
        <a class="get_today_outages">За сегодня</a>
      </li>
      <li>
        <a class="get_yesterday_outages">За вчера</a>
      </li>
      <li>
        <a class="get_week_outages">За текущую неделю</a>
      </li>
      <li>
        <a class="get_last_week_outages">За предыдущую неделю</a>
      </li>
    </ul>
  </div>
  <div class="col-md-10">
    {% if user.check_access('numbers/create_outage') %}
    <p>
      <a class="get_dialog_create_outage">Создать</a>
    </p>
    {% endif %}
    <ol class="outages">{% include "outages/outages.tpl" %}</ol>
  </div>
</div>