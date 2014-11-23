<option value="0">Выберите пользователя</option>
{% for user in response.group.get_users() %}
<option value="{{ user.get_id() }}">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}</option>
{% endfor %}