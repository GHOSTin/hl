{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	{% block js %}
		$('.query[query_id = {{query.id}}] .query-users').append(get_hidden_content())
	{% endblock js %}
	{% block html %}
		{% if component.users.users %}
			{% if component.users.structure[query.id] != false %}
				<ul class="query-sub">
					<li>Ответственные <span class="get_dialog_add_user cm" type="manager">добавить</span>
						<ul>
						{% for number_id in component.users.structure[query.id].manager %}
							{% set user = component.users.users[number_id] %}
							<li user="{{user.id}}">{{user.firstname}} {{user.lastname}}</li>
						{% endfor %}
						</ul>
					</li>
					<li>Исполнители <span class="get_dialog_add_user cm" type="performer">добавить</span>
						<ul>
						{% for number_id in component.users.structure[query.id].performer %}
							{% set user = component.users.users[number_id] %}
							<li user="{{user.id}}">{{user.firstname}} {{user.lastname}}</li>
						{% endfor %}
						</ul>
					</li>
					<li>Наблюдатели
						<ul>
						{% for number_id in component.users.structure[query.id].observer %}
							{% set user = component.users.users[number_id] %}
							<li user="{{user.id}}">{{user.firstname}} {{user.lastname}}</li>
						{% endfor %}
						</ul>
					</li>
				<ul>
			{% endif %}
		{% else %}
			Нет пользователей.
		{% endif %}
	{% endblock html %}
{% endif %}