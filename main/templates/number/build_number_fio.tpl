<div class="row">
    <div class="col-md-5">
        <h3>Информация о лицевом счете</h3>
        <ul class="list-unstyled">
            <li>Лицевой счет: {{ number.get_number() }} <a class="get_dialog_edit_number" number="{{ number.get_id() }}">изменить</a></li>
            {% if user.check_access('numbers/generate_password') %}
            <li>Пароль для личного кабинета <a class="get_dialog_generate_password" number="{{ number.get_id() }}">генерировать</a></li>
            {% endif %}
            <li>Задолженость: {{ number.get_debt()|number_format(2, '.', ' ') }} руб.</li>
            <li>
              <a href="/number/accruals?id={{ number.get_id() }}" target="_blank">Начисления</a>
            </li>
            <li>
              <a href="/number/query_of_number?id={{ number.get_id() }}" target="_blank">Заявки ({{ number.get_queries().count() }})</a>
            </li>
            <li>
              <a href="/numbers/{{ number.get_id() }}/meterages/" target="_blank">Показания</a>
            </li>
        </ul>
    </div>
    <div class="col-md-5">
        <h3>Контактная информаци {% if user.check_access('numbers/contacts') %} <a class="get_dialog_contacts" number="{{ number.get_id() }}"><i class="fa fa-pencil"></i></a>{% endif %}</h3>
        <a href="/numbers/{{ number.get_id() }}/contacts/history/" target="_blank">История изменения контактных данных{% if number.get_relevance_time %} на {{ number.get_relevance_time()|date("d.m.Y") }}{% endif %}</a>
        <ul class="list-unstyled">
            <li>Владелец: {{ number.get_fio() }}</li>
            {% if number.get_telephone() %}
                <li><i class="fa fa-phone fa-lg"></i> {{ number.get_telephone() }}</li>
            {% endif %}
            {% if number.get_cellphone() %}
                <li><i class="fa fa-mobile fa-lg"></i> <span class="cellphone">{{ number.get_cellphone() }}</span></li>
            {% endif %}
            {% if number.get_email() %}
                <li><i class="fa fa-envelope-o"></i> {{ number.get_email() }}</li>
            {% endif %}
            <li>Контактные данные из заявок: <a href="/number/contact_info?id={{ number.get_id() }}" target="_blank">просмотреть</a></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
      <h3>События</h3>
      <ul class="list-unstyled">
      {% for event in number.get_events() %}
        <li class="well">
          <p>{{ event.get_time()|date("d.m.Y") }} {{ event.get_name() }}</p>
          <p>{{ event.get_description() }}</p>
          <ul class="list-unstyled project-files">
          {% for file in event.get_files() %}
            <li>
              <a href="/files/{{ file.get_path() }}">
                <i class="fa fa-file"></i> {{ file.get_name() }}
              </a>
            </li>
          {%endfor %}
          </ul>
        </li>
      {% else %}
        <li>Нет ни одного события</li>
      {% endfor %}
      </ul>
    </div>
</div>