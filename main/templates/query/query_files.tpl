{% for file in query.get_files() %}
  {% set user = file.get_user() %}
  <li path="{{ file.get_path() }}">
    <a href="/files/{{ file.get_path() }}">{{ file.get_name() }}</a> ({{ file.get_time()|date('H:i d.m.Y') }} {{ user.get_lastname() }} {{ user.get_firstname()|slice(0, 1) }}. {{ user.get_middlename()|slice(0, 1)}}.){% if query.get_status() in ['open', 'working', 'reopen'] %}<span class="fa fa-trash get_dialog_delete_file"></span>{% endif %}
  </li>
{% endfor %}