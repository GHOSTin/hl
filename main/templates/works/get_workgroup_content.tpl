<div class="workgroup-content">
  <a class="btn btn-sm btn-primary btn-outline m-t-xs m-b-xs get_dialog_rename_workgroup">Переименовать</a>
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
        <div class="panel-body">
          <button type="button" class="btn btn-primary btn-xs m-b-sm get_dialog_add_work">Добавить работу</button>
          <div class="no-padding">
            <ul class="works list-group">
              {% for work in workgroup.get_works() %}
                <li class="work list-group-item {% if loop.first %}fist-item{% endif %}" work_id="{{ work.get_id() }}">
                  <div class="pull-right">
                    <a class="btn btn-xs btn-white get_dialog_exclude_work"><i class="fa fa-trash-o"></i></a>
                  </div>
                  {{ work.get_name() }}
                </li>
              {% endfor %}
            </ul>
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="events">
        <div class="panel-body">
          <button class="btn btn-primary btn-xs m-b-sm get_dialog_add_event">Добавить событие</button>
          <div class="no-padding">
            <ul class="events list-group">
              {% for event in workgroup.get_events() %}
                <li class="event list-group-item {% if loop.first %}fist-item{% endif %}" event_id="{{ event.get_id() }}">
                  <div class="pull-right">
                    <a class="btn btn-xs btn-white get_dialog_exclude_event"><i class="fa fa-trash-o"></i></a>
                  </div>
                  {{ event.get_name() }}
                </li>
              {% endfor %}
            </ul>
          </div>
        </div>
      </div>
      <div role="tabpanel" class="tab-pane" id="phrases">
        <div class="panel-body">
          <button class="btn btn-primary btn-xs m-b-sm get_dialog_add_phrase">Добавить фразу</button>
          <div class="no-padding">
            <ul class="phrases list-group">
              {% for phrase in workgroup.get_phrases() %}
                <li class="phrase list-group-item {% if loop.first %}fist-item{% endif %}" data-phrase_id="{{ phrase.get_id() }}">
                <div class="pull-right">
                  <div class="btn-group">
                    <a class="btn btn-xs btn-white get_dialog_edit_phrase"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-xs btn-white get_dialog_remove_phrase"><i class="fa fa-trash-o"></i></a>
                  </div>
                </div>
                  {{ phrase.get_text() }}
               </li>
              </li>
            {% endfor %}
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>