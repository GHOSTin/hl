{% for user in users %}
    <li class="user" user="{{ user.id }}">
        <div class="user-fio">{{ user.lastname }} {{ user.firstname }} {{ user.middlename }} <a class="get_dialog_exclude_user">исключить</a></div>
    </li>
{% endfor %}