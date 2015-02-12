<div class="row">
  <div class="col-md-12">
    <h3>Информация о доме</h3>
    <ul class="list-unstyled">
      <li>Участок: {{ house.get_department().get_name() }} <a class="cm get_dialog_edit_department">изменить</a></li>
      <li>
        <a href="/number/query_of_house?id={{ house.get_id() }}" target="_blank">Заявки на дом</a>
      </li>
    </ul>
  </div>
</div>
<div class="row" style="margin-top: 20px;">
  <div class="col-md-12">
  <h3>Лицевые счета</h3>
  <ul class="numbers nav nav-tabs nav-stacked">
  {% for flat in house.get_flats().getValues()|natsort %}
    {% for number in flat.get_numbers() %}
    <li class="number" number="{{ number.get_id() }}">
      <a class="get_number_content">кв. №{{ number.get_flat().get_number() }} {{ number.get_fio() }} (л/с №{{ number.get_number() }})</a>
    </li>
    {% endfor %}
  {% endfor %}
  </ul>
  </div>
</div>