<li class="ibox-content event" event_id="{{ n2e.id }}">
  <p>{{ n2e.time|date("d.m.Y") }} {{ n2e.event.name }}<a class="get_dialog_edit_event" event_id="{{ n2e.id }}"><i class="fa fa-pencil"></i></a></p>
  <p>{{ n2e.number.address }}</p>
  <p>{{ n2e.description }}</p>
  <div class="row">
    <div class="col-md-6">
      <ul class="list-unstyled project-files">
      {% for file in n2e.files %}
        <li>
          <a href="/files/{{ file.path }}">
            <i class="fa fa-file"></i>{{ file.name }}
          </a>
        </li>
      {%endfor %}
      </ul>
    </div>
  </div>
</li>