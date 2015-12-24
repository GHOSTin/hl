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
    <h3>Последние 5 отключений</h3>
    <ul>
      {% for outage in house.get_outages().slice(0, 5) %}
      <li>
        c {{ outage.get_begin()|date("d.m.Y") }} по {{ outage.get_target()|date("d.m.Y") }} {{ outage.get_category().get_name() }}: {{ outage.get_description() }}
      </li>
      {% endfor %}
      <li>
        <a href="/numbers/houses/{{ house.get_id() }}/outages/" target="_blank">Все отключения</a>
      </li>
    </ul>
    {% set debtors = house.get_debtors(20000) %}
    {% if debtors is not empty %}
    <div class="table-responsive ibox-content">
      <h4>Злостные неплательщики</h4>
      <table class="table table-striped table-condensed">
        <thead>
          <th>Квартира</th>
          <th>Задолженость в рублях</th>
        </thead>
        <tbody>
        {% for numbers in debtors %}
          {% for number in numbers %}
          <tr>
            <td>кв. №{{ number.get_flat().get_number() }}</td>
            <td>{{ number.get_debt()|number_format(2, '.', ' ') }}</td>
          </tr>
          {% endfor %}
        {% endfor %}
        </tbody>
      </table>
    </div>
    {% endif %}
  </div>
</div>