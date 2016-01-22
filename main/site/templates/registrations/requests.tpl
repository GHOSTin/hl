{% for request in requests %}
  <li request_id="{{ request.id }}" class="well">
  <h2>Запрос л/с №{{ request.number.number }}</h2>
  <div class="row">
    <div class="col-md-4">
    <h3>Данные из запроса</h3>
    <dl class="dl-horizontal">
      <dt>Адресс</dt>
      <dd>{{ request.address }}</dd>
      <dt>ФИО</dt>
      <dd>{{ request.fio }}</dd>
      <dt>email</dt>
      <dd>{{ request.email }}</dd>
      <dt>Сотовый</dt>
      <dd>{{ request.cellphone }}</dd>
      <dt>Телефон</dt>
      <dd>{{ request.telephone }}</dd>
    </dl>
    </div>
    <div class="col-md-4">
      <h3>Данные из жилфонда</h3>
      <dl class="dl-horizontal">
        <dt>Адресс</dt>
        <dd>{{ request.number.address }}</dd>
        <dt>ФИО</dt>
        <dd>{{ request.number.fio }}</dd>
        <dt>email</dt>
        <dd>{{ request.number.email }}</dd>
        <dt>Сотовый</dt>
        <dd>{{ request.number.cellphone }}</dd>
        <dt>Телефон</dt>
        <dd>{{ request.number.telephone }}</dd>
      </dl>
    </div>
  </div>
  </li>
{% endfor %}