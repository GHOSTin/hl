<li class="ibox-content event" event_id="{{ event.get_id() }}">
  <p>{{ event.get_time()|date("d.m.Y") }} {{ event.get_name() }}<a class="get_dialog_edit_event" event_id="{{ event.get_id() }}"><i class="fa fa-pencil"></i></a> </p>
  <p>{{ event.get_number().get_address() }}</p>
  <p>{{ event.get_description() }}</p>
  <div class="row">
    <div class="col-md-6">
      <ul class="list-unstyled project-files">
        {% for file in event.get_files() %}
          <li>
            <a href="/files/{{ file.get_path() }}">
              <i class="fa fa-file"></i>{{ file.get_name() }}
            </a>
          </li>
        {%endfor %}
      </ul>
    </div>
  </div>
</li>