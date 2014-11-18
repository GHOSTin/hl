{% for user in group.get_users() %}
    <li class="user" user="{{ user.get_id() }}">
        <div class="user-fio">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }} <a class="get_dialog_exclude_user">исключить</a></div>
    </li>
{% endfor %}