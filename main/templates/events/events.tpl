{% for event in events %}
<li class="ibox-content">
  <p>{{ event.get_time()|date("d.m.Y") }} {{ event.get_name() }}</p>
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
{% endfor %}