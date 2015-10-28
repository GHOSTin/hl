<div class="workgroup-content">
  <ul class="nav nav-pills">
    <li>
      <a class="get_dialog_add_work">Добавить работу</a>
    </li>
    <li>
      <a class="get_dialog_add_event">Добавить событие</a>
    </li>
    <li>
      <a class="get_dialog_add_phrase">Добавить фразу</a>
    </li>
    <li>
      <a class="get_dialog_rename_workgroup">Переименовать</a>
    </li>
  </ul>
  <h3>Работы</h3>
  <ul class="works">
  {% for work in workgroup.get_works() %}
    <li class="work" work_id="{{ work.get_id() }}">{{ work.get_name() }} <a class="get_dialog_exclude_work">исключить</a></li>
  {% endfor %}
  </ul>
  <h3>События</h3>
  <ul class="events">
  {% for event in workgroup.get_events() %}
    <li class="event" event_id="{{ event.get_id() }}">{{ event.get_name() }} <a class="get_dialog_exclude_event">исключить</a></li>
  {% endfor %}
  </ul>
  <h3>Фразы</h3>
  <ul class="phrases">
  {% for phrase in workgroup.get_phrases() %}
    <li class="phrase" phrase="{{ phrase.get_id() }}">{{ phrase.get_text() }}</li>
  {% endfor %}
  </ul>
</div>