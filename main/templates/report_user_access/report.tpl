{% extends "default.tpl" %}
{% set statuses = {"true": "Активен", "false": "Заблокирован"} %}

{% block component %}
<table class="table table-bordered">
  <tbody>
{% for user in users %}
  {% set departments_restrictions = user.get_restriction('departments') %}
  {% set categories_restrictions = user.get_restriction('categories') %}
  {% if loop.index0 % 10 == 0 %}
  <tr>
    <th>Пользователь</th>
    <th>Статус</th>
    <th>Заявки</th>
    <th>Жилой фонд</th>
    <th>Задачи</th>
    <th>Система</th>
    <th>Отчеты</th>
    <th>Показания</th>
    <th>Импорт</th>
    <th>Ограничения по участкам</th>
    <th>Ограничения по категории</th>
  </tr>
  {% endif %}
  <tr>
    <td>{{ user.get_fio() }}</td>
    <td>{{ statuses[user.get_status()] }}</td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('queries/general_access') %}<li>Общий доступ</li>{% endif %}
        {% if user.check_access('queries/create_query') %}<li>Создание заявки</li>{% endif %}
        {% if user.check_access('queries/analize_request') %}<li>Анализ запросов из личного кабинета</li>{% endif %}
        {% if user.check_access('queries/save_contacts') %}<li>Сохранение контактные данные в жилфонд</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('numbers/general_access') %}<li>Общий доступ</li>{% endif %}
        {% if user.check_access('numbers/generate_password') %}<li>Генерация пароля</li>{% endif %}
        {% if user.check_access('numbers/contacts') %}<li>Изменение контактных данных</li>{% endif %}
        {% if user.check_access('numbers/create_outage') %}<li>Создание отключений</li>{% endif %}
        {% if user.check_access('numbers/create_event') %}<li>Создание события</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('tasks/general_access') %}<li>Общий доступ</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('system/general_access') %}<li>Общий доступ</li>{% endif %}
        {% if user.check_access('system/api_key') %}<li>Ключи API</li>{% endif %}
        {% if user.check_access('system/config') %}<li>Просмотр конфигурации</li>{% endif %}
        {% if user.check_access('system/logs') %}<li>Логи</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('reports/general_access') %}<li>Общий доступ</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('metrics/general_access') %}<li>Общий доступ</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% if user.check_access('import/general_access') %}<li>Общий доступ</li>{% endif %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% for department in departments %}
          {% if department.get_id() in departments_restrictions %}<li>{{ department.get_name() }}</li>{% endif %}
        {% endfor %}
      </ul>
    </td>
    <td>
      <ul class="list-unstyled">
        {% for categories in categories %}
          {% if categories.get_id() in categories_restrictions %}<li>{{ categories.get_name() }}</li>{% endif %}
        {% endfor %}
      </ul>
    </td>
  </tr>
{% endfor %}
  <tbody>
</table>
{% endblock %}