<div class="row">
  <div class="col-md-2">
    <ul>
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
    <p>
      <a class="get_dialog_create_outage">Создать</a>
    </p>
    <ol class="outages">{% include "outages/outages.tpl" %}</ol>
  </div>
</div>