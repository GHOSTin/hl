<div class="panel panel-default m-t-sm m-b-sm">
  <div class="panel-heading">
    Ответственные
    {% if query.get_status() in ['open', 'working', 'reopen'] %}
      <a class="btn btn-xs btn-white get_dialog_add_user" type="manager">добавить</a>
    {% endif %}
  </div>
  <div class="panel-body">
    <ul class="query-users-manager list-group">
      {% for user in query.get_managers() %}
        <li class="list-group-item" user="{{ user.get_id() }}" type="manager">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}
        {% if query.get_status() in ['open', 'working', 'reopen'] %}
          <a class="get_dialog_remove_user">удалить</a>
        {% endif %}
        </li>
      {% endfor %}
    </ul>
  </div>
</div>
<div class="panel panel-default m-b-sm">
  <div class="panel-heading">
    Исполнители
    {% if query.get_status() in ['open', 'working', 'reopen'] %}
      <a class="btn btn-xs btn-white get_dialog_add_user" type="performer">добавить</a>
    {% endif %}
  </div>
  <div class="panel-body">
    <ul class="query-users-performer list-group">
      {% for user in query.get_performers() %}
        <li class="list-group-item" user="{{ user.get_id() }}" type="performer">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}
          {% if query.get_status() in ['open', 'working', 'reopen'] %}
            <a class="get_dialog_remove_user">удалить</a>
          {% endif %}
        </li>
      {% endfor %}
    </ul>
  </div>
</div>