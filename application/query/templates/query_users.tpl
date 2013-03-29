<li>Ответственные <span class="get_dialog_add_user cm" type="manager">добавить</span>
	<ul class="query-users-manager">
	{% for number_id in component.users.structure[query.id].manager %}
		{% set user = component.users.users[number_id] %}
		<li user="{{user.id}}" type="manager">{{user.lastname}} {{user.firstname}} {{user.middlename}} <span class="cm get_dialog_remove_user">удалить</span></li>
	{% endfor %}
	</ul>
</li>
<li>Исполнители <span class="get_dialog_add_user cm" type="performer">добавить</span>
	<ul class="query-users-performer">
	{% for number_id in component.users.structure[query.id].performer %}
		{% set user = component.users.users[number_id] %}
		<li user="{{user.id}}" type="performer">{{user.lastname}} {{user.firstname}} {{user.middlename}} <span class="cm get_dialog_remove_user">удалить</span></li>
	{% endfor %}
	</ul>
</li>
<li>Наблюдатели
	<ul>
	{% for number_id in component.users.structure[query.id].observer %}
		{% set user = component.users.users[number_id] %}
		<li user="{{user.id}}">{{user.lastname}} {{user.firstname}} {{user.middlename}}</li>
	{% endfor %}
	</ul>
</li>