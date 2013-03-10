{% extends "ajax.tpl" %}
{% if query != false %}
	{% block js %}
			$('.query[query_id={{query.id}}]').html(get_temp_html())
			.addClass('get_query_content');
	{% endblock js %}
	{% block html %}
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
	{% endblock html %}
{% endif %}