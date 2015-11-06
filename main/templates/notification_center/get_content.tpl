<ul class='user-list'>
{% for user in users %}
  <li class="chat-user" user_id="{{ user.get_id() }}">
    <div class="chat-user-name">{{ user.get_lastname() }}</div>
  </li>
{% endfor %}
</ul>