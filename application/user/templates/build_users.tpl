{% for user in users %}
    <li class="user" user="{{ user.id }}">
        <div class="user-fio get_user_content">{{ user.lastname }} {{ user.firstname }} {{ user.middlename }}</div>
    </li>
{% endfor %}