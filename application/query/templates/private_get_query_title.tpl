{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% block js %}
			$('.query[query_id={{component.queries[0].id}}]').html(get_hidden_content())
			.addClass('get_query_content');
	{% endblock js %}
	{% block html %}
	<div>
		{% if component.queries[0].initiator == 'number' %}
			<img src="/templates/default/images/icons/xfn-friend.png" />
		{% else %}
			<img src="/templates/default/images/icons/home-medium.png" />
		{% endif %}
		<b>№{{component.queries[0].number}}</b> {{component.queries[0].time_open|date("H:i d.m.y")}}
		{{component.queries[0].street_name}}, дом №{{component.queries[0].house_number}}
	</div>
	{{component.queries[0].description}}
	{% endblock html %}
{% endif %}