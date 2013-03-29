{% extends "ajax.tpl" %}
{% if component.queries != false %}
	{% set query = component.queries[0] %}
	{% block js %}
		$('.query[query_id = {{query.id}}] .query-users').append(get_hidden_content())
	{% endblock js %}
	{% block html %}
		<ul class="query-sub">
			{% include '@query/query_users.tpl' %}
		<ul>
	{% endblock html %}
{% endif %}