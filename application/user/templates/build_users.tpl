{% for user in users %}
    <li user="{{ user.id }}">{{ user.lastname }} {{ user.firstname }} {{ user.middlename }}</li>
{% endfor %}