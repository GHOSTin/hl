{% extends "ajax.tpl" %}
{% set query = response.query %}
{% block js %}
	$('.query[query_id = {{ query.get_id() }}] .query-users').append(get_hidden_content())
{% endblock js %}
{% block html %}
	<ul class="query-sub">
		{% include '@query/query_users.tpl' %}
	<ul>
{% endblock html %}