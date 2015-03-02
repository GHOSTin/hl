<div class="row">
    <div class="col-md-5">
        <h3>Информация о лицевом счете</h3>
        <ul class="list-unstyled">
            <li>Владелец: {{ number.get_fio() }} <a class="get_dialog_edit_number_fio">изменить</a></li>
            <li>Лицевой счет: {{ number.get_number() }} <a class="get_dialog_edit_number">изменить</a></li>
        </ul>
    </div>
    <div class="col-md-5">
        <h3>Контактная информаци</h3>
        <ul class="list-unstyled">
            <li>Телефон: {{ number.get_telephone() }} <a class="get_dialog_edit_number_telephone">изменить</a></li>
            <li>Сотовый телефон: {{ number.get_cellphone() }} <a class="get_dialog_edit_number_cellphone">изменить</a></li>
            <li>email: {{ number.get_email() }} <a class="get_dialog_edit_number_email">изменить</a></li>
            <li>Контактные данные из заявок: <a href="/number/contact_info?id={{ number.get_id() }}" target="_blank">просмотреть</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <h3>Дополнительная информация</h3>
        <ul class="list-unstyled">
          <li>
            <a href="/number/accruals?id={{ number.get_id() }}" target="_blank">Начисления</a>
          </li>
          <li>
            <a href="/number/query_of_number?id={{ number.get_id() }}" target="_blank">Заявки</a>
          </li>
        </ul>
    </div>
    <div class="col-md-5">
      <h3>События</h3>
      <ul class="list-unstyled">
        <li>
          <a class="get_dialog_add_event">Добавить</a>
        </li>
      </ul>
      <ul class="events">
      {% for event in number.get_events() %}
        <li event_id="{{ event.get_id() }}" time="{{ event.get_time() }}">{{ event.get_time()|date("d.m.Y") }} {{ event.get_name() }} <a class="get_dialog_exclude_event">исключить</a></li>
      {% else %}
        <li>Нет ни одного события</li>
      {% endfor %}
      </ul>
    </div>
</div>