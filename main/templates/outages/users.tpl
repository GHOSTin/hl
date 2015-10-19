<option>Выберите пользователя</option>
{% for user in group.get_users() %}
<option value="{{ user.get_id() }}">{{ user.get_fio() }}</option>
{% endfor %}