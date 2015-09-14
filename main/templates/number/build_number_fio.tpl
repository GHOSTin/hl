<div class="row">
    <div class="col-md-5">
        <h3>Информация о лицевом счете</h3>
        <ul class="list-unstyled">
            <li>Лицевой счет: {{ number.get_number() }} <a class="get_dialog_edit_number">изменить</a></li>
            {% if user.check_access('numbers/generate_password') %}
            <li>Пароль для личного кабинета <a class="get_dialog_generate_password">генерировать</a></li>
            {% endif %}
            <li>Задолженость: {{ number.get_debt() }} руб.</li>
        </ul>
    </div>
    <div class="col-md-5">
        <h3>Контактная информаци {% if user.check_access('numbers/contacts') %}<small><a class="get_dialog_contacts">изменить</a></small>{% endif %}</h3>
        <a href="/numbers/{{ number.get_id() }}/contacts/history/" target="_blank">История изменения контактных данных</a>
        <ul class="list-unstyled">
            <li>Владелец: {{ number.get_fio() }}</li>
            <li>Стационарный телефон: {{ number.get_telephone() }}</li>
            <li>Сотовый телефон: <span class="cellphone">{{ number.get_cellphone() }}</span></li>
            <li>email: {{ number.get_email() }}</li>
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