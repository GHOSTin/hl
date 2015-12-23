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
  <div class="tabs-container">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#works" aria-controls="works" role="tab" data-toggle="tab">Работы</a></li>
      <li role="presentation"><a href="#events" aria-controls="events" role="tab" data-toggle="tab">События</a></li>
      <li role="presentation"><a href="#phrases" aria-controls="phrases" role="tab" data-toggle="tab">Фразы</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
      <div role="tabpanel" class="tab-pane active" id="works">
        <ul class="works list-unstyled panel-body">
          {% for work in workgroup.get_works() %}
            <li class="work" work_id="{{ work.get_id() }}">{{ work.get_name() }} <a class="get_dialog_exclude_work">исключить</a></li>
          {% endfor %}
        </ul>
      </div>
      <div role="tabpanel" class="tab-pane" id="events">
        <ul class="events list-unstyled panel-body">
          {% for event in workgroup.get_events() %}
            <li class="event" event_id="{{ event.get_id() }}">{{ event.get_name() }} <a class="get_dialog_exclude_event">исключить</a></li>
          {% endfor %}
        </ul>
      </div>
      <div role="tabpanel" class="tab-pane" id="phrases">
        <ul class="phrases list-unstyled panel-body">
          {% for phrase in workgroup.get_phrases() %}
            <li class="phrase" phrase="{{ phrase.get_id() }}">{{ phrase.get_text() }} <i class="fa fa-close get_dialog_remove_phrase"></i> <i class="fa fa-edit get_dialog_edit_phrase"></i></li>
          {% endfor %}
        </ul>
      </div>
    </div>
  </div>
</div>