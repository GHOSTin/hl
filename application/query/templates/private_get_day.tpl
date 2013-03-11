{% extends "ajax.tpl" %}
{% block html %}
	{% if component.queries != false %}
		{% for query in component.queries %}
			<div class="query get_query_content
			{% if query.status in ['working','open', 'close', 'reopen']%}
				query_status_{{query.status}}
			{% else %}
				query_status_default
			{% endif %}
			" query_id="{{query.id}}">
				<div>
					{% if query.initiator == 'number' %}
						<img src="/templates/default/images/icons/xfn-friend.png" />
					{% else %}
						<img src="/templates/default/images/icons/home-medium.png" />
					{% endif %}
					<b>№{{query.number}}</b> {{query.time_open|date("H:i d.m.y")}}
					{{query.street_name}}, дом №{{query.house_number}}
				</div>
				{{query.description}}
			</div>
		{% endfor %}
	{% else %}
	    Нет заявок
	{% endif %}
{% endblock html %}