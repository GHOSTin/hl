<li class="ibox event" event_id="{{ n2e.id }}">
  <div class="ibox-title">
    <h5>{{ n2e.event.name }}</h5>
    <div class="ibox-tools">
      <a class="text-primary get_dialog_edit_event" event_id="{{ n2e.id }}"><i class="fa fa-pencil"></i></a>
      <a class="text-primary get_dialog_exclude_event" event_id="{{ n2e.id }}"><i class="fa fa-trash-o"></i></a>
    </div>
  </div>
  <div class="ibox-content">
    <p>{{ n2e.number.address }}</p>
    <p>{{ n2e.description }}</p>
    <div class="row">
      <div class="col-md-6">
        <a class="btn btn-xs btn-white get_dialog_added_files"><i class="fa fa-paperclip"></i> Добавить</a>
        <ul class="list-unstyled project-files">
        {% for file in n2e.files %}
          <li data-file="{{ file.path }}">
            <a href="/files/{{ file.path }}">
              <i class="fa fa-file"></i> {{ file.name }}
            </a>
            <a class="btn btn-white btn-outline btn-xs delete-file">
              <i class="fa fa-trash-o"></i>
            </a>
          </li>
        {%endfor %}
        </ul>
      </div>
    </div>
  </div>
</li>