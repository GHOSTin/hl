<div class="row">
  <div class="col-md-6">
    <h3>Выборки</h3>
    <p>
      Внимание! Выборки не учитывают настройки фильтров и работают независимо. Выборки призваны упростить диспетчеру выполнение повседневних задач.
    </p>
    <h4>Незакрытые заявки за последние 6 месяцев</h4>
    <ul class="list-unstyled">
      <li>
        <a class="selection_noclose" time="{{ current }}">Незакрытые заявки за текущий месяц</a>
      </li>
    {% for key, month in months %}
      <li>
        <a class="selection_noclose" time="{{ key }}">Незакрытые заявки за {{ month }}</a>
      </li>
    {% endfor %}
    </ul>
  </div>
</div>