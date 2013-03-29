<li>Ответственные 
	{% if query.status in ['open', 'working', 'reopen'] %}
	<span class="get_dialog_add_user cm" type="manager">добавить</span>
	{% endif %}
	<ul class="query-users-manager">
	{% for number_id in component.users.structure[query.id].manager %}
		{% set user = component.users.users[number_id] %}
		<li user="{{user.id}}" type="manager">{{user.lastname}} {{user.firstname}} {{user.middlename}}
		{% if query.status in ['open', 'working', 'reopen'] %}
		<span class="cm get_dialog_remove_user">удалить</span></li>
		{% endif %}
	{% endfor %}
	</ul>
</li>
<li>Исполнители 
	{% if query.status in ['open', 'working', 'reopen'] %}
	<span class="get_dialog_add_user cm" type="performer">добавить</span>
	{% endif %}
	<ul class="query-users-performer">
	{% for number_id in component.users.structure[query.id].performer %}
		{% set user = component.users.users[number_id] %}
		<li user="{{user.id}}" type="performer">{{user.lastname}} {{user.firstname}} {{user.middlename}}
		{% if query.status in ['open', 'working', 'reopen'] %}
		<span class="cm get_dialog_remove_user">удалить</span></li>
		{% endif %}
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