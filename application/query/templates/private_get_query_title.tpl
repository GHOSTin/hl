{% extends "ajax.tpl" %}
{% if query != false %}
	{% block js %}
			$('.query[query_id={{component.query.id}}]').html(get_hidden_content())
			.addClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div>
		{% if query.initiator == 'number' %}
			<img src="/templates/default/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/templates/default/images/icons/home-medium.png" />
		{% endif %}
		<b>№{{component.query.number}}</b> {{component.query.time_open|date("H:i d.m.y")}}
		{{component.query.street_name}}, дом №{{component.query.house_number}}
	</div>
	{{component.query.description}}
	{% endblock html %}
{% endif %}