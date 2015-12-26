{% for file in query.get_files() %}
  {% set user = file.get_user() %}
  <li path="{{ file.get_path() }}">
    <a href="/files/{{ file.get_path() }}">{{ file.get_name() }}</a>
    <small>({{ file.get_time()|date('d.m.Y H:i') }} {{ user.get_lastname() }} {{ user.get_firstname()|slice(0, 1) }}. {{ user.get_middlename()|slice(0, 1)}}.)</small>
    {% if query.get_status() in ['open', 'working', 'reopen'] %}<a class="btn btn-xs btn-white btn-outline get_dialog_delete_file"><i class="fa fa-trash-o"></i></a>{% endif %}
  </li>
{% endfor %}