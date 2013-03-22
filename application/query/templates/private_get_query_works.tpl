{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	{% block js %}
		$('.query[query_id = {{query.id}}] .query-works').append(get_hidden_content())
	{% endblock js %}
	{% block html %}
		{% if component.works.works %}
			{% if component.works.structure[query.id] != false %}
				<ol class="query-sub">
				{% for current_work in component.works.structure[query.id] %}
					{% set work = component.works.works[current_work.work_id] %}
					<li work="{{work.id}}">{{work.name}}
						<div>Время: с {{current_work.time_open|date('H:i d.m.Y')}} до {{current_work.time_close|date('H:i d.m.Y')}}</div>
					</li>
				{% endfor %}
				</ol>
			{% endif %}
		{% else %}
			Нет пользователей.
		{% endif %}
	{% endblock html %}
{% endif %}