<li>Ответственные 
	{% if query.get_status() in ['open', 'working', 'reopen'] %}
	<span class="get_dialog_add_user cm" type="manager">добавить</span>
	{% endif %}
	<ul class="query-users-manager">
	{% for user in query.get_managers() %}
		<li user="{{ user.get_id() }}" type="manager">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}
		{% if query.get_status() in ['open', 'working', 'reopen'] %}
		<span class="cm get_dialog_remove_user">удалить</span></li>
		{% endif %}
	{% endfor %}
	</ul>
</li>
<li>Исполнители 
	{% if query.get_status() in ['open', 'working', 'reopen'] %}
	<span class="get_dialog_add_user cm" type="performer">добавить</span>
	{% endif %}
	<ul class="query-users-performer">
	{% for user in query.get_performers() %}
		<li user="{{ user.get_id() }}" type="performer">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}
		{% if query.get_status() in ['open', 'working', 'reopen'] %}
		<span class="cm get_dialog_remove_user">удалить</span></li>
		{% endif %}
	{% endfor %}
	</ul>
</li>
<li>Наблюдатели
	<ul>
	{% for user in query.get_observers() %}
		<li user="{{ user.get_id() }}">{{ user.get_lastname() }} {{ user.get_firstname() }} {{ user.get_middlename() }}</li>
	{% endfor %}
	</ul>
</li>