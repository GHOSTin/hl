<div class="row">
  <div class="col-md-6">
    <h3>Лицевые счета</h3>
    <ul class="numbers list-unstyled">
    {% for flat in house.get_flats().getValues()|natsort %}
      {% for number in flat.get_numbers() %}
      <li class="number" number="{{ number.get_id() }}">
        <a class="get_number_content">кв. №{{ number.get_flat().get_number() }} {{ number.get_fio() }} (л/с №{{ number.get_number() }})</a>
      </li>
      {% endfor %}
    {% endfor %}
    </ul>
  </div>
  <div class="col-md-6">
    <h3>Информация о доме</h3>
    <ul class="list-unstyled">
      <li>Участок: {{ house.get_department().get_name() }} <a class="cm get_dialog_edit_department" house="{{ house.get_id() }}">изменить</a></li>
      <li>Задолженость: {{ house.get_debt()|number_format(2, '.', ' ') }} руб.</li>
      <li>
        <a href="/number/houses/{{ house.get_id() }}/queries/" target="_blank">Заявки ({{ house.get_queries().count() }})</a>
      </li>
    </ul>
  </div>
</div>