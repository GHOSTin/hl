{% for user in users %}
    <li class="user" user="{{ user.id }}">
        <div class="user-fio">{{ user.lastname }} {{ user.firstname }} {{ user.middlename }}</div>
    </li>
{% endfor %}