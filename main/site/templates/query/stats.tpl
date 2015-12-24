<h4 class="text-center">Дневная статистика</h4>
<div class="row">
  <div class="col-md-5">
    <canvas id="chart" width="120px" height="120px"></canvas>
  </div>
  <div class="col-md-7">
    <ul class="list-unstyled">
      <li class="queries_legend queries_open">Открытых заявок: {{ stat.open }}</li>
      <li class="queries_legend queries_working">Заявок в работе: {{ stat.working }}</li>
      <li class="queries_legend queries_close">Закрытых заявок: {{ stat.close }}</li>
      <li class="queries_legend queries_reopen">Переоткрытых заявок: {{ stat.reopen }}</li>
      <li class="queries_legend">Всех заявок: {{ stat.sum }}</li>
    </ul>
  </div>
</div>