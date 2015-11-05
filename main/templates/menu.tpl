<li>
  <a tabindex="-1" href="/">Главная</a>
</li>
<li class="divider"></li>
{% if user.check_access('tasks/general_access') %}
<li>
  <a tabindex="-1" href="/task/">Задачи</a>
</li>
{% endif %}
{% if user.check_access('queries/general_access') %}
<li>
  <a tabindex="-1" href="/query/">Заявки</a>
</li>
{% endif %}
{% if user.check_access('numbers/general_access') %}
<li>
  <a tabindex="-1" href="/number/">Жилой фонд</a>
</li>
{% endif %}
{% if user.check_access('reports/general_access') %}
<li>
  <a tabindex="-1" href="/reports/">Отчеты</a>
</li>
{% endif %}
{% if user.check_access('metrics/general_access') %}
<li>
  <a tabindex="-1" href="/metrics/">Показания</a>
</li>
{% endif %}
{% if user.check_access('system/general_access') %}
<li>
  <a tabindex="-1" href="/system/">Система</a>
</li>
{% endif %}
<li class="divider"></li>
<li>
  <a tabindex="-1" href="/about/">О программе</a>
</li>
<li>
  <a tabindex="-1" href="/logout/">Выход</a>
</li>