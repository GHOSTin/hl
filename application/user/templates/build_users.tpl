{% for user in users %}
    <li class="user" user="{{ user.get_id() }}">
        <div class="user-fio get_user_content">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}</div>
    </li>
{% endfor %}