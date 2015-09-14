{% extends "ajax.tpl" %}

{% block js %}
$('.user[user = {{ user.get_id() }}] .user-information').html(get_hidden_content());
{% endblock %}

{% block html %}
<div class="row" style="padding-bottom:40px;">
  <div class="col-md-2">
    <h4>Заявки</h4>
    <ul class="list-unstyled">
      <li class="access" value="queries/general_access"><input type="checkbox"{% if user.check_access('queries/general_access') %}checked=""{% endif %}> Общий доступ</li>
      <li class="access" value="queries/create_query"><input type="checkbox"{% if user.check_access('queries/create_query') %}checked=""{% endif %}> Создание заявки</li>
      <li class="access" value="queries/analize_request"><input type="checkbox"{% if user.check_access('queries/analize_request') %}checked=""{% endif %}> Анализ запросов из личного кабинета</li>
    </ul>
  </div>
  <div class="col-md-2">
    <h4>Жилой фонд</h4>
    <ul class="list-unstyled">
      <li class="access" value="numbers/general_access"><input type="checkbox"{% if user.check_access('numbers/general_access') %}checked=""{% endif %}> Общий доступ</li>
      <li class="access" value="numbers/generate_password"><input type="checkbox"{% if user.check_access('numbers/generate_password') %}checked=""{% endif %}> Генерация пароля</li>
      <li class="access" value="numbers/contacts"><input type="checkbox"{% if user.check_access('numbers/contacts') %}checked=""{% endif %}> Изменение контактных данных</li>
    </ul>
  </div>
  <div class="col-md-2">
    <h4>Задачи</h4>
    <ul class="list-unstyled">
      <li class="access" value="tasks/general_access"><input type="checkbox"{% if user.check_access('tasks/general_access') %}checked=""{% endif %}> Общий доступ</li>
    </ul>
  </div>
  <div class="col-md-2">
    <h4>Система</h4>
    <ul class="list-unstyled">
      <li class="access" value="system/general_access"><input type="checkbox"{% if user.check_access('system/general_access') %}checked=""{% endif %}> Общий доступ</li>
      <li class="access" value="system/api_key"><input type="checkbox"{% if user.check_access('system/api_key') %}checked=""{% endif %}> Ключи API</li>
      <li class="access" value="system/config"><input type="checkbox"{% if user.check_access('system/config') %}checked=""{% endif %}> Просмотр конфигурации</li>
      <li class="access" value="system/logs"><input type="checkbox"{% if user.check_access('system/logs') %}checked=""{% endif %}> Логи</li>
    </ul>
  </div>
</div>
<div class="row" style="padding-bottom:20px;">
  <div class="col-md-2">
    <h4>Отчеты</h4>
    <ul class="list-unstyled">
      <li class="access" value="reports/general_access"><input type="checkbox"{% if user.check_access('reports/general_access') %}checked=""{% endif %}> Общий доступ</li>
    </ul>
  </div>
  <div class="col-md-2">
    <h4>Показания</h4>
    <ul class="list-unstyled">
      <li class="access" value="metrics/general_access"><input type="checkbox"{% if user.check_access('metrics/general_access') %}checked=""{% endif %}> Общий доступ</li>
    </ul>
  </div>
  <div class="col-md-2">
    <h4>Импорт</h4>
    <ul class="list-unstyled">
      <li class="access" value="import/general_access"><input type="checkbox"{% if user.check_access('import/general_access') %}checked=""{% endif %}> Общий доступ</li>
    </ul>
  </div>
</div>
{% endblock html %}