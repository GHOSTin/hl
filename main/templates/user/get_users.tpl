<div>
  <a class="btn btn-default get_dialog_create_user">Создать нового пользователя</a>
</div>
<ul class="users list-unstyled">
{% for user in users %}
  <li class="user" user="{{ user.get_id() }}">
      <div class="user-fio get_user_content">{{ user.get_fio() }}</div>
  </li>
{% endfor %}
</ul>