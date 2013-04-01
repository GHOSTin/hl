<option value="0">Выберите пользователя</option>
{% for user in component.users %}
<option value="{{user.id}}">{{user.lastname}} {{user.firstname}} {{user.middlename}}</option>
{% endfor %}