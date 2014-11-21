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
  {% include 'number/build_number_titles.tpl' %}
  </div>
</div>