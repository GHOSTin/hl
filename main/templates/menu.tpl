<li class="active">
  <a tabindex="-1" href="/">
    <i class="fa fa-home"></i>
    <span>Главная</span>
  </a>
</li>
<li class="divider"></li>
{% if user.check_access('tasks/general_access') %}
<li>
  <a tabindex="-1" href="/task/">
    <i class="fa fa-tasks"></i>
    <span>Задачи</span>
  </a>
</li>
{% endif %}
{% if user.check_access('queries/general_access') %}
<li>
  <a tabindex="-1" href="/query/">
    <i class="fa fa-sticky-note"></i>
    <span>Заявки</span>
  </a>
</li>
{% endif %}
{% if user.check_access('numbers/general_access') %}
<li>
  <a tabindex="-1" href="/number/">
    <i class="fa fa-building-o"></i>
    <span>Жилой фонд</span>
  </a>
</li>
{% endif %}
{% if user.check_access('reports/general_access') %}
<li>
  <a tabindex="-1" href="/reports/">
    <i class="fa fa-pie-chart"></i>
    <span>Отчеты</span>
  </a>
</li>
{% endif %}
{% if user.check_access('metrics/general_access') %}
<li>
  <a tabindex="-1" href="/metrics/">
    <i class="fa fa-tachometer"></i>
    <span>Показания</span>
  </a>
</li>
{% endif %}
{% if user.check_access('system/general_access') %}
<li>
  <a tabindex="-1" href="/system/">
    <i class="fa fa-cogs"></i>
    <span>Система</span>
  </a>
</li>
{% endif %}
<li class="divider"></li>
<li>
  <a tabindex="-1" href="http://docs.mshc.mlsco.ru/" target="_blank">
    <i class="fa fa-info-circle"></i>
    <span>О программе</span>
  </a>
</li>